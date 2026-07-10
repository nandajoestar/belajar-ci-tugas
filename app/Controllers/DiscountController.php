<?php

namespace App\Controllers;

use App\Controllers\BaseController;
use App\Models\DiscountModel;

class DiscountController extends BaseController
{
    protected $discountModel;

    function __construct()
    {
        $this->discountModel = new DiscountModel();
    }

    /**
     * Cek apakah user adalah admin, jika bukan redirect ke home
     */
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
            'discounts' => $this->discountModel->orderBy('tanggal', 'ASC')->findAll(),
            'discount'  => $this->discountModel->getTodayDiscount(),
        ];

        if (session()->getFlashData('errors')) {
            $data['errors'] = session()->getFlashData('errors');
        }
        if (session()->getFlashData('editErrors')) {
            $data['editErrors'] = session()->getFlashData('editErrors');
        }
        if (session()->getFlashData('oldInput')) {
            $data['oldInput'] = session()->getFlashData('oldInput');
        }
        if (session()->getFlashData('editData')) {
            $data['editData'] = session()->getFlashData('editData');
        }

        return view('diskon/index', $data);
    }

    public function create()
    {
        $redirect = $this->checkAdmin();
        if ($redirect) return $redirect;

        $rules = [
            'tanggal' => 'required|is_unique[discount.tanggal]',
            'nominal' => 'required|numeric',
        ];

        $messages = [
            'tanggal' => [
                'is_unique' => 'The tanggal field must contain a unique value.',
            ],
        ];

        if (!$this->validate($rules, $messages)) {
            session()->setFlashdata('errors', $this->validator->getErrors());
            session()->setFlashdata('oldInput', $this->request->getPost());
            return redirect()->to('diskon');
        }

        $this->discountModel->skipValidation(true)->insert([
            'tanggal' => $this->request->getPost('tanggal'),
            'nominal' => $this->request->getPost('nominal'),
        ]);

        return redirect()->to('diskon')->with('success', 'Data Diskon Berhasil Ditambah');
    }

    public function edit($id)
    {
        $redirect = $this->checkAdmin();
        if ($redirect) return $redirect;

        $discount = $this->discountModel->find($id);

        if (!$discount) {
            return redirect()->to('diskon')->with('error', 'Data tidak ditemukan');
        }

        $rules = [
            'nominal' => 'required|numeric',
        ];

        if (!$this->validate($rules)) {
            session()->setFlashdata('editErrors', $this->validator->getErrors());
            session()->setFlashdata('editData', ['id' => $id]);
            return redirect()->to('diskon');
        }

        $this->discountModel->skipValidation(true)->update($id, [
            'nominal' => $this->request->getPost('nominal'),
        ]);

        return redirect()->to('diskon')->with('success', 'Data Diskon Berhasil Diubah');
    }

    public function delete($id)
    {
        $redirect = $this->checkAdmin();
        if ($redirect) return $redirect;

        $this->discountModel->delete($id);
        return redirect()->to('diskon')->with('success', 'Data Diskon Berhasil Dihapus');
    }
}

