<?php

use CodeIgniter\Router\RouteCollection;

/**
 * @var RouteCollection $routes
 */

$routes->get('/', 'Home::index', ['filter' => 'auth']);

$routes->get('login', 'AuthController::login');
$routes->post('login', 'AuthController::login');
$routes->get('logout', 'AuthController::logout');

$routes->group('produk', ['filter' => 'auth'], function ($routes) { 
    $routes->get('', 'ProdukController::index');
    $routes->post('', 'ProdukController::create');
    $routes->post('edit/(:any)', 'ProdukController::edit/$1');
    $routes->get('delete/(:any)', 'ProdukController::delete/$1');
    $routes->get('download', 'ProdukController::download');
});

$routes->group('keranjang', ['filter' => 'auth'], function ($routes) {
    $routes->get('', 'TransaksiController::index');
    $routes->post('', 'TransaksiController::cart_add');
    $routes->post('edit', 'TransaksiController::cart_edit');
    $routes->get('delete/(:any)', 'TransaksiController::cart_delete/$1');
    $routes->get('clear', 'TransaksiController::cart_clear');
    $routes->get('checkout', 'TransaksiController::checkout');
    $routes->post('order', 'TransaksiController::order');
});

$routes->get('keranjang', 'TransaksiController::index', ['filter' => 'auth']);

// Route Diskon - hanya admin
$routes->group('diskon', ['filter' => 'auth'], function ($routes) {
    $routes->get('', 'DiscountController::index');
    $routes->post('', 'DiscountController::create');
    $routes->post('edit/(:any)', 'DiscountController::edit/$1');
    $routes->get('delete/(:any)', 'DiscountController::delete/$1');
});

// Route Pembelian - hanya admin
$routes->group('pembelian', ['filter' => 'auth'], function ($routes) {
    $routes->get('', 'PembelianController::index');
    $routes->get('detail/(:any)', 'PembelianController::detail/$1');
    $routes->get('ubah-status/(:any)', 'PembelianController::ubahStatus/$1');
});

// API Routes - dengan Bearer token authentication
$routes->group('api', ['filter' => 'apiauth'], function ($routes) {
    // API Products
    $routes->get('products', 'Api\ProductController::index');
    $routes->get('products/(:num)', 'Api\ProductController::show/$1');
    $routes->post('products', 'Api\ProductController::create');
    $routes->put('products/(:num)', 'Api\ProductController::update/$1');
    $routes->delete('products/(:num)', 'Api\ProductController::delete/$1');

    // API Discount
    $routes->get('discount', 'Api\DiscountController::index');
    $routes->get('discount/(:num)', 'Api\DiscountController::show/$1');
    $routes->post('discount', 'Api\DiscountController::create');
    $routes->put('discount/(:num)', 'Api\DiscountController::update/$1');
    $routes->delete('discount/(:num)', 'Api\DiscountController::delete/$1');
});
