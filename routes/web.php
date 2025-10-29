<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;
use Inertia\Inertia;
use App\Models\Product;

Route::get('/', function () {
    return Inertia::render('Welcome');
})->name('home');

// Update this dashboard route - add 'admin' to middleware
Route::get('dashboard', function () {
    return Inertia::render('Dashboard');
})->middleware(['auth', 'verified', 'admin'])->name('dashboard'); // Added 'admin' here

// Admin dashboard (utama)
Route::get('/admin/dashboard', function () {
    return Inertia::render('Dashboard');
})->middleware(['auth', 'verified', 'admin'])->name('admin.dashboard');

// Admin produk
use App\Models\KategoriProduct;
use App\Http\Controllers\Admin\ProductController;

Route::get('/admin/products', function () {
    $products = Product::with(['subkategori.kategori'])->latest()->get();
    $categories = KategoriProduct::with('subkategories')->get();

    return Inertia::render('Products', [
        'products' => $products,
        'categories' => $categories,
    ]);
})->middleware(['auth', 'verified', 'admin'])->name('admin.products');

// Product CRUD (store / update / destroy)
Route::post('/admin/products', [ProductController::class, 'store'])
    ->middleware(['auth', 'verified', 'admin'])
    ->name('admin.products.store');

Route::put('/admin/products/{product}', [ProductController::class, 'update'])
    ->middleware(['auth', 'verified', 'admin'])
    ->name('admin.products.update');

Route::delete('/admin/products/{product}', [ProductController::class, 'destroy'])
    ->middleware(['auth', 'verified', 'admin'])
    ->name('admin.products.destroy');

// Shop page for logged-in users
Route::get('/shop', function () {
    return Inertia::render('ShopPage');
})->middleware(['auth', 'verified'])->name('shop');

//Compare Page
Route::get('/compare', fn() => inertia('ComparePage'));


require __DIR__.'/settings.php';
require __DIR__.'/auth.php';