<?php

namespace App\Controllers;

use App\Controllers\BaseController;
use App\Models\TransactionModel;
use App\Models\TransactionDetailModel;
use App\Models\DiscountModel;

class PembelianController extends BaseController
{
    protected $transactionModel;
    protected $transactionDetailModel;
    protected $discountModel;

    public function __construct()
    {
        helper(['number', 'form']);
        $this->transactionModel       = new TransactionModel();
        $this->transactionDetailModel = new TransactionDetailModel();
        $this->discountModel          = new DiscountModel();
    }

    private function checkAdmin()
    {
        if (session()->get('role') !== 'admin') {
            return redirect()->to('/')->with('error', 'Akses ditolak. Hanya admin yang bisa mengakses halaman ini.');
        }
        return null;
    }

    public function index()
    {
        $redirect = $this->checkAdmin();
        if ($redirect) return $redirect;

        $data = [
            'transactions' => $this->transactionModel->orderBy('id', 'DESC')->findAll(),
            'discount'     => $this->discountModel->getTodayDiscount(),
        ];

        return view('pembelian/index', $data);
    }

    public function detail($id)
    {
        $redirect = $this->checkAdmin();
        if ($redirect) return $redirect;

        $details = $this->transactionDetailModel->getDetailWithProduct($id);

        return $this->response->setJSON($details);
    }

    public function ubahStatus($id)
    {
        $redirect = $this->checkAdmin();
        if ($redirect) return $redirect;

        $transaksi = $this->transactionModel->find($id);

        if (!$transaksi) {
            return redirect()->to('pembelian')->with('error', 'Data tidak ditemukan');
        }

        // Toggle status: 0 -> 1, 1 -> 0
        $statusBaru = $transaksi['status'] == 0 ? 1 : 0;

        $this->transactionModel->update($id, ['status' => $statusBaru]);

        $pesan = $statusBaru == 1 ? 'Status diubah menjadi Sudah Selesai' : 'Status diubah menjadi Belum Selesai';

        return redirect()->to('pembelian')->with('success', $pesan);
    }
}
