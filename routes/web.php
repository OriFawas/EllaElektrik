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
Route::get('/admin/products', function () {
    $products = Product::with(['subkategori.kategori'])->latest()->get();

    return Inertia::render('Products', [
        'products' => $products,
    ]);
})->middleware(['auth', 'verified', 'admin'])->name('admin.products');

// Shop page for logged-in users
Route::get('/shop', function () {
    return Inertia::render('ShopPage');
})->middleware(['auth', 'verified'])->name('shop');

require __DIR__.'/settings.php';
require __DIR__.'/auth.php';