<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\CheckoutController;
use App\Http\Controllers\ItemController;
use App\Http\Controllers\KategoriController;
use App\Http\Controllers\LogsController;
use App\Http\Controllers\MaintenanceController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

// Route untuk mendapatkan user yang terautentikasi 
Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

// Route untuk autentikasi
Route::prefix('auth')->group(function () {
    Route::post('login', [AuthController::class, 'login']);
    Route::post('logout', [AuthController::class, 'logout'])->middleware('auth:sanctum');
});

// Route untuk admin dengan middleware auth:sanctum dan IsAdmin
Route::post('admin/register', [AuthController::class, 'register']);
Route::middleware(['auth:sanctum', 'IsAdmin'])->group(function () {
    Route::prefix('admin')->group(function () {
        Route::post('kategori', [KategoriController::class, 'store']);
        Route::put('kategori/{id}', [KategoriController::class, 'update']);
        Route::delete('kategori/{id}', [KategoriController::class, 'delete']);
        Route::get('activity-logs', [LogsController::class, 'index']);
    });
});

// Route untuk barang dan transaksi dengan middleware auth:sanctum dan LogActivity
Route::middleware(['auth:sanctum', 'LogActivity'])->group(function () {
    // Barang
    Route::get('kategori', [KategoriController::class, 'index']);
    Route::resource('barang', ItemController::class);

    // Peminjaman
    Route::post('pinjam/{item_id}', [CheckoutController::class, 'checkoutBarang']);
    Route::post('kembali/{item_id}', [CheckoutController::class, 'returnBarang']);

    // Maintenance
    Route::post('/maintenance/create/{item_id}', [MaintenanceController::class, 'createMaintenance']);
    Route::put('/maintenance/process/{maintenance_id}', [MaintenanceController::class, 'processMaintenance']);
    Route::put('/maintenance/complete/{maintenance_id}', [MaintenanceController::class, 'completeMaintenance']);

    // history
    Route::get('history/pinjaman', [CheckoutController::class, 'getAllPinjaman']);
    Route::get('history/maintenance', [MaintenanceController::class, 'index']);
});
