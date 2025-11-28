<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

// API routes for frontend shop
Route::get('/subkategori', [\App\Http\Controllers\Api\SubkategoriController::class, 'index']);
Route::get('/products', [\App\Http\Controllers\Api\ProductController::class, 'index']);

use App\Http\Controllers\Admin\ProductController;
use App\Models\KategoriProduct;
use App\Models\SubkategoriProduct;

// Cart endpoints are defined in web.php to use session-based auth
// Products API
Route::middleware(['auth:sanctum'])->group(function () {
	Route::get('/admin/products', [ProductController::class, 'index']);
	Route::delete('/admin/products/{product}', [ProductController::class, 'destroy']);

	// Taxonomy lists for filters
	Route::get('/admin/categories', function () {
		return KategoriProduct::select('id', 'name')->orderBy('name')->get();
	});
	Route::get('/admin/subcategories', function () {
		return SubkategoriProduct::select('id', 'name', 'kategori_product_id')->orderBy('name')->get();
	});
});
