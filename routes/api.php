<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\CartController;
use App\Http\Controllers\Admin\ProductController;
use App\Models\KategoriProduct;
use App\Models\SubkategoriProduct;

// Test endpoint to verify no web middleware  
Route::get('/test-middleware', function (Request $request) {
    return response()->json([
        'message' => 'API working without web middleware',
        'session_started' => $request->hasSession(),
        'middleware' => 'api',
    ]);
});

// Public API routes (no authentication required)
Route::get('/subkategori', [\App\Http\Controllers\Api\SubkategoriController::class, 'index']);
Route::get('/products', [\App\Http\Controllers\Api\ProductController::class, 'index']);

// Authentication endpoints
Route::post('/login', [AuthController::class, 'login']);

// Protected API routes (require authentication via token OR session)
Route::middleware(['auth:sanctum,web'])->group(function () {
    // User info and logout
    Route::get('/user', [AuthController::class, 'user']);
    Route::post('/logout', [AuthController::class, 'logout']);
    Route::post('/logout-all', [AuthController::class, 'logoutAll']);
    
    // Cart endpoints (authenticated users - supports both Postman tokens AND web session)
    Route::prefix('cart')->group(function () {
        Route::get('/', [CartController::class, 'show']);
        Route::get('/count', [CartController::class, 'count']);
        Route::post('/items', [CartController::class, 'store']);
        Route::put('/items/{itemId}', [CartController::class, 'update']);
        Route::delete('/items/{itemId}', [CartController::class, 'destroy']);
    });
    
    // Admin-only endpoints (require admin:* ability)
    Route::prefix('admin')->middleware(\App\Http\Middleware\CheckTokenAbility::class)->group(function () {
        Route::get('/products', [ProductController::class, 'index']);
        Route::delete('/products/{product}', [ProductController::class, 'destroy']);

        // Taxonomy lists for filters
        Route::get('/categories', function () {
            return response()->json(KategoriProduct::select('id', 'name')->orderBy('name')->get());
        });
        
        Route::get('/subcategories', function () {
            return response()->json(SubkategoriProduct::select('id', 'name', 'kategori_product_id')->orderBy('name')->get());
        });
    });
});
