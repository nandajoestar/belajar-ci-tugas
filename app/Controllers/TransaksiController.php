<?php

namespace App\Controllers;

use App\Controllers\BaseController;
use App\Models\DiscountModel;
use App\Models\TransactionModel;
use App\Models\TransactionDetailModel;
use App\Models\ProductModel;

class TransaksiController extends BaseController
{
    protected $cart;
    protected $discountModel;
    protected $transactionModel;
    protected $transactionDetailModel;
    protected $productModel;

    public function __construct()
    {
        helper(['number', 'form']);
        $this->cart                   = service('cart');
        $this->discountModel          = new DiscountModel();
        $this->transactionModel       = new TransactionModel();
        $this->transactionDetailModel = new TransactionDetailModel();
        $this->productModel           = new ProductModel();
    }

    public function index()
    {
        $discount = $this->discountModel->getTodayDiscount();
        $items    = $this->cart->contents();

        // Hitung total dengan diskon
        $totalDiskon = 0;
        if ($discount && !empty($items)) {
            foreach ($items as $item) {
                $totalDiskon += $discount['nominal'] * $item['qty'];
            }
        }

        $totalAsli  = $this->cart->total();
        $totalAkhir = max(0, $totalAsli - $totalDiskon);

        $data = [
            'items'      => $items,
            'total'      => $totalAkhir,
            'totalAsli'  => $totalAsli,
            'discount'   => $discount,
        ];

        return view('v_keranjang', $data);
    }

    public function cart_add()
    {
        $this->cart->insert([
            'id'      => $this->request->getPost('id'),
            'qty'     => 1,
            'price'   => $this->request->getPost('harga'),
            'name'    => $this->request->getPost('nama'),
            'options' => [
                'foto' => $this->request->getPost('foto')
            ]
        ]);

        session()->setFlashdata(
            'success',
            'Produk berhasil ditambahkan ke keranjang.
            <a href="' . base_url('keranjang') . '">Lihat</a>'
        );

        return redirect()->to(base_url('/'));
    }

    public function cart_edit()
    {
        $i = 1;
        foreach ($this->cart->contents() as $item) {
            $qty = $this->request->getPost('qty' . $i++);

            $this->cart->update([
                'rowid' => $item['rowid'],
                'qty'   => $qty
            ]);
        }

        session()->setFlashdata(
            'success',
            'Keranjang berhasil diperbarui'
        );

        return redirect()->to(base_url('keranjang'));
    }

    public function cart_delete($rowid)
    {
        $this->cart->remove($rowid);

        session()->setFlashdata(
            'success',
            'Produk berhasil dihapus dari keranjang'
        );

        return redirect()->to(base_url('keranjang'));
    }

    public function cart_clear()
    {
        $this->cart->destroy();

        session()->setFlashdata(
            'success',
            'Keranjang berhasil dikosongkan'
        );

        return redirect()->to(base_url('keranjang'));
    }

    /**
     * Halaman Checkout
     */
    public function checkout()
    {
        $items    = $this->cart->contents();
        $discount = $this->discountModel->getTodayDiscount();

        if (empty($items)) {
            return redirect()->to('keranjang')->with('success', 'Keranjang masih kosong');
        }

        $totalDiskon = 0;
        if ($discount) {
            foreach ($items as $item) {
                $totalDiskon += $discount['nominal'] * $item['qty'];
            }
        }

        $totalAsli  = $this->cart->total();
        $totalAkhir = max(0, $totalAsli - $totalDiskon);

        $data = [
            'items'      => $items,
            'total'      => $totalAkhir,
            'totalAsli'  => $totalAsli,
            'discount'   => $discount,
        ];

        return view('v_checkout', $data);
    }

    /**
     * Simpan pesanan ke database
     */
    public function order()
    {
        $items    = $this->cart->contents();
        $discount = $this->discountModel->getTodayDiscount();

        if (empty($items)) {
            return redirect()->to('keranjang');
        }

        $totalDiskon = 0;
        if ($discount) {
            foreach ($items as $item) {
                $totalDiskon += $discount['nominal'] * $item['qty'];
            }
        }

        $totalAsli  = $this->cart->total();
        $ongkir     = $this->request->getPost('ongkir') ?? 0;
        $totalAkhir = max(0, $totalAsli - $totalDiskon) + $ongkir;

        // Simpan transaksi
        $transactionId = $this->transactionModel->insert([
            'username'    => session()->get('username'),
            'total_harga' => $totalAkhir,
            'alamat'      => $this->request->getPost('alamat'),
            'ongkir'      => $ongkir,
            'status'      => 0,
        ]);

        // Simpan detail transaksi
        foreach ($items as $item) {
            $nominalDiskon = $discount ? $discount['nominal'] : 0;
            $hargaSetelahDiskon = max(0, $item['price'] - $nominalDiskon);
            $subtotal = $hargaSetelahDiskon * $item['qty'];

            $this->transactionDetailModel->insert([
                'transaction_id' => $transactionId,
                'product_id'     => $item['id'],
                'jumlah'         => $item['qty'],
                'diskon'         => $nominalDiskon,
                'subtotal_harga' => $subtotal,
            ]);
        }

        // Kosongkan keranjang setelah order
        $this->cart->destroy();

        return redirect()->to('/')->with('success', 'Pesanan berhasil dibuat!');
    }
}
