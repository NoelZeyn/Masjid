<?php

use App\Http\Controllers\Acara\AcaraController;
use App\Http\Controllers\Acara\DokumentasiAcaraController;
use App\Http\Controllers\Acara\PesertaAcaraController;
use App\Http\Controllers\Authentication\AdminController;
use App\Http\Controllers\Inventaris\BarangController;
use App\Http\Controllers\Inventaris\RiwayatPenggunaanController;
use App\Http\Controllers\Inventaris\SupplierController;
use App\Http\Controllers\Inventaris\TransaksiController;
use App\Http\Controllers\ProfileController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');

Route::group(['middleware' => 'api'], function ($router) {
    Route::post('register', [AdminController::class, 'register']);
    Route::post('login', [AdminController::class, 'login']);
    Route::post('logout', [AdminController::class, 'logout']);
    Route::post('refresh', [AdminController::class, 'refresh']);
    Route::post('me', [AdminController::class, 'me']);
});
Route::group(['middleware' => 'api'], function ($router) {
    Route::get('profile', [ProfileController::class, 'showProfile']);
    Route::put('profile', [ProfileController::class, 'updateProfile']);
    Route::post('editPassword', [ProfileController::class, 'updatePassword']);
});
Route::group(['middleware' => 'api'], function ($router) {
    Route::apiResource('acara', AcaraController::class);
    Route::get('search', [AcaraController::class, 'searchAcara']);
    Route::get('kategori', [AcaraController::class, 'showKategori']);
});
Route::group(['middleware' => 'api'], function ($router) {
    Route::apiResource('dokumentasi-acara', DokumentasiAcaraController::class);
    Route::get('search-dokumentasi', [DokumentasiAcaraController::class, 'searchDokumentasiAcara']);
    Route::get('kategori', [AcaraController::class, 'showKategori']);
});

Route::group(['middleware' => 'api'], function ($router) {
    Route::apiResource('peserta-acara', PesertaAcaraController::class);
    Route::get('search-peserta', [PesertaAcaraController::class, 'search']);
    Route::get('kategori', [AcaraController::class, 'showKategori']);
});

Route::group(['middleware' => 'api'], function ($router) {
    Route::apiResource( 'barang', BarangController::class);
    Route::apiResource( 'supplier', SupplierController::class);
    Route::get('supplier/search', [SupplierController::class, 'searchSupplier']);
    Route::apiResource( 'transaksi', TransaksiController::class);
    Route::apiResource( 'riwayat-penggunaan', RiwayatPenggunaanController::class);
});
Route::post('check', [AdminController::class, 'checkEmail']);