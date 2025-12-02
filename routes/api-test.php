<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

// Test endpoint to verify no web middleware
Route::get('/test-middleware', function (Request $request) {
    return response()->json([
        'message' => 'API working without web middleware',
        'session_started' => $request->hasSession(),
        'has_csrf_token' => $request->session()?->has('_token') ?? false,
        'middleware' => $request->route()?->middleware() ?? [],
    ]);
});
