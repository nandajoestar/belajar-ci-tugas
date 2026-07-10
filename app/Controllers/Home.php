<?php

namespace App\Controllers;

use App\Models\ProductModel;
use App\Models\DiscountModel;

class Home extends BaseController
{
    protected $productModel;
    protected $discountModel;

    function __construct(){
        helper(['number', 'form']);
        $this->productModel  = new ProductModel();
        $this->discountModel = new DiscountModel();
    }

    public function index()
    {
        $discount = $this->discountModel->getTodayDiscount();

        return view('v_home', [
            'products' => $this->productModel->findAll(),
            'discount' => $discount,
        ]);
    }
}
