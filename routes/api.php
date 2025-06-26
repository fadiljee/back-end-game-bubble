<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\LeaderboardController;

Route::post('login', [AuthController::class, 'login']);

// Group route yang harus pakai token:
Route::middleware('auth:sanctum')->group(function () {

    // Materi
    Route::get('materi', [AuthController::class, 'index']);
    Route::get('materi/cari', [AuthController::class, 'searchByTitle']);
    Route::get('materi/{id}', [AuthController::class, 'show']);

    // Kuis
    Route::get('kuis', [AuthController::class, 'kuis']);
    Route::get('kuis/{id}', [AuthController::class, 'kuisShow']);

    // Simpan hasil kuis
    Route::post('hasil-kuis', [AuthController::class, 'storeHasilKuis']);

    Route::get('/leaderboard', [AuthController::class, 'lead']);

    // Rute untuk mengirim skor (POST)
    Route::post('/leaderboard', [AuthController::class, 'store']);


    // Logout (jika perlu)
    Route::post('logout', [AuthController::class, 'logout']);
});
