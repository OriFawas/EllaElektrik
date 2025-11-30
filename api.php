<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

// API routes for frontend shop
Route::get('/kategori', [\App\Http\Controllers\Api\KategoriController::class, 'index']);
Route::get('/subkategori', [\App\Http\Controllers\Api\SubkategoriController::class, 'index']);
Route::get('/products', [\App\Http\Controllers\Api\ProductController::class, 'index']);

// Cart endpoints are defined in web.php to use session-based auth
