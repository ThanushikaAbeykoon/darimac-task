<?php

use Illuminate\Http\Request;
use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\FormController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\GoogleController;

// Test endpoint to verify API is working
Route::get('/test', function () {
    return response()->json(['message' => 'API is working!', 'status' => 'success']);
});

// Test form submission endpoint (for debugging)
Route::post('/test-form', function (Request $request) {
    return response()->json([
        'message' => 'Test form endpoint working!',
        'received_data' => $request->all(),
        'status' => 'success'
    ]);
});

// Database health check endpoint
Route::get('/health', function () {
    try {
        // Test database connection
        DB::connection()->getPdo();
        $dbStatus = 'connected';
    } catch (Exception $e) {
        $dbStatus = 'failed: ' . $e->getMessage();
    }

    // Check if tables exist
    $tables = [];
    if (Schema::hasTable('users')) {
        $tables['users'] = 'exists';
    } else {
        $tables['users'] = 'missing';
    }

    if (Schema::hasTable('forms')) {
        $tables['forms'] = 'exists';
    } else {
        $tables['forms'] = 'missing';
    }

    return response()->json([
        'status' => 'ok',
        'database' => $dbStatus,
        'tables' => $tables,
        'timestamp' => now()->toISOString()
    ]);
});

Route::post('/login', [AuthController::class, 'login']);
Route::post('/register', [AuthController::class, 'register']);

Route::middleware('auth:sanctum')->group(function () {
    Route::get('/forms', [FormController::class, 'index'])->name('api.forms.index');
    Route::post('/forms', [FormController::class, 'store'])->name('api.forms.store');
    Route::get('/forms/{id}/pdf', [FormController::class, 'downloadPdf'])->name('api.forms.download');
    Route::get('/forms/{id}/qrcode', [FormController::class, 'getQrCode'])->name('api.forms.qrcode');
    Route::get('/forms/{id}/pdf-url', [FormController::class, 'getPdfUrl'])->name('api.forms.pdf-url');
    Route::get('/forms/{id}/qrcode-url', [FormController::class, 'getQrCodeUrl'])->name('api.forms.qrcode-url');
    Route::get('/user', function (Request $request) {
        return $request->user();
    });
    Route::post('/logout', [AuthController::class, 'logout']);
});

Route::get('/auth/google', [GoogleController::class, 'redirectToGoogle'])->name('api.login.google');
Route::get('/auth/google/callback', [GoogleController::class, 'handleGoogleCallback']);
