<?php

namespace App\Controllers\Api;

use App\Controllers\BaseController;
use App\Models\ProductModel;
use CodeIgniter\API\ResponseTrait;

class ProductController extends BaseController
{
    use ResponseTrait;

    protected $productModel;

    public function __construct()
    {
        $this->productModel = new ProductModel();
    }

    // GET /api/products?page=1&per_page=5
    public function index()
    {
        $page     = $this->request->getGet('page')     ?? 1;
        $per_page = $this->request->getGet('per_page') ?? 10;

        $products = $this->productModel->paginate($per_page, 'default', $page);
        $pager    = $this->productModel->pager;

        return $this->respond([
            'status'  => 200,
            'data'    => $products,
            'pager'   => [
                'page'       => (int) $page,
                'per_page'   => (int) $per_page,
                'total'      => $pager->getTotal('default'),
                'page_count' => $pager->getPageCount('default'),
            ],
        ]);
    }

    // GET /api/products/:id
    public function show($id = null)
    {
        $product = $this->productModel->find($id);

        if (!$product) {
            return $this->failNotFound('Product not found');
        }

        return $this->respond([
            'status' => 200,
            'data'   => $product,
        ]);
    }

    // POST /api/products
    public function create()
    {
        $data = $this->request->getJSON(true);

        if (!$data) {
            return $this->failValidationErrors('Request body is required');
        }

        $id = $this->productModel->insert($data);

        if (!$id) {
            return $this->failValidationErrors($this->productModel->errors());
        }

        return $this->respondCreated([
            'status'  => 201,
            'message' => 'Product created successfully',
            'data'    => $this->productModel->find($id),
        ]);
    }

    // PUT /api/products/:id
    public function update($id = null)
    {
        $product = $this->productModel->find($id);

        if (!$product) {
            return $this->failNotFound('Product not found');
        }

        $data = $this->request->getJSON(true);

        if (!$data) {
            return $this->failValidationErrors('Request body is required');
        }

        $this->productModel->update($id, $data);

        return $this->respond([
            'status'  => 200,
            'message' => 'Product updated successfully',
            'data'    => $this->productModel->find($id),
        ]);
    }

    // DELETE /api/products/:id
    public function delete($id = null)
    {
        $product = $this->productModel->find($id);

        if (!$product) {
            return $this->failNotFound('Product not found');
        }

        $this->productModel->delete($id);

        return $this->respondDeleted([
            'status'  => 200,
            'message' => 'Product deleted successfully',
        ]);
    }
}
