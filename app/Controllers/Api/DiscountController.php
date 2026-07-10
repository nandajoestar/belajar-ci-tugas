<?php

namespace App\Controllers\Api;

use App\Controllers\BaseController;
use App\Models\DiscountModel;
use CodeIgniter\API\ResponseTrait;

class DiscountController extends BaseController
{
    use ResponseTrait;

    protected $discountModel;

    public function __construct()
    {
        $this->discountModel = new DiscountModel();
    }

    // GET /api/discount?page=1&per_page=5
    public function index()
    {
        $page     = $this->request->getGet('page')     ?? 1;
        $per_page = $this->request->getGet('per_page') ?? 10;

        $discounts = $this->discountModel->orderBy('tanggal', 'ASC')->paginate($per_page, 'default', $page);
        $pager     = $this->discountModel->pager;

        return $this->respond([
            'status' => 200,
            'data'   => $discounts,
            'pager'  => [
                'page'       => (int) $page,
                'per_page'   => (int) $per_page,
                'total'      => $pager->getTotal('default'),
                'page_count' => $pager->getPageCount('default'),
            ],
        ]);
    }

    // GET /api/discount/:id
    public function show($id = null)
    {
        $discount = $this->discountModel->find($id);

        if (!$discount) {
            return $this->failNotFound('Discount not found');
        }

        return $this->respond([
            'status' => 200,
            'data'   => $discount,
        ]);
    }

    // POST /api/discount
    public function create()
    {
        $data = $this->request->getJSON(true);

        if (!$data) {
            return $this->failValidationErrors('Request body is required');
        }

        // Validasi: tanggal harus unik
        $existing = $this->discountModel->where('tanggal', $data['tanggal'] ?? '')->first();
        if ($existing) {
            return $this->failValidationErrors('Discount for this date already exists');
        }

        $id = $this->discountModel->skipValidation(true)->insert($data);

        if (!$id) {
            return $this->failValidationErrors($this->discountModel->errors());
        }

        return $this->respondCreated([
            'status'  => 201,
            'message' => 'Discount created successfully',
            'data'    => $this->discountModel->find($id),
        ]);
    }

    // PUT /api/discount/:id
    public function update($id = null)
    {
        $discount = $this->discountModel->find($id);

        if (!$discount) {
            return $this->failNotFound('Discount not found');
        }

        $data = $this->request->getJSON(true);

        if (!$data) {
            return $this->failValidationErrors('Request body is required');
        }

        // Hanya nominal yang bisa diubah via API PUT
        $updateData = ['nominal' => $data['nominal'] ?? $discount['nominal']];

        $this->discountModel->skipValidation(true)->update($id, $updateData);

        return $this->respond([
            'status'  => 200,
            'message' => 'Discount updated successfully',
            'data'    => $this->discountModel->find($id),
        ]);
    }

    // DELETE /api/discount/:id
    public function delete($id = null)
    {
        $discount = $this->discountModel->find($id);

        if (!$discount) {
            return $this->failNotFound('Discount not found');
        }

        $this->discountModel->delete($id);

        return $this->respondDeleted([
            'status'  => 200,
            'message' => 'Discount deleted successfully',
        ]);
    }
}
