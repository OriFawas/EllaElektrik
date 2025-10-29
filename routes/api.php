<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

// API routes for frontend shop
Route::get('/subkategori', [\App\Http\Controllers\Api\SubkategoriController::class, 'index']);
Route::get('/products', [\App\Http\Controllers\Api\ProductController::class, 'index']);
