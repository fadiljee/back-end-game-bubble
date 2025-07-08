<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\quizController;
use App\Http\Controllers\MateriController;
use App\Http\Controllers\UserController;
use App\Http\Middleware\LoginCheck;
use App\Http\Controllers\ProfileController;
use App\Http\Middleware\LoggedIn;

Route::get('/', function () {
    return view('admin.login');
});

Route::middleware(LoginCheck::class)->group(function () {
    Route::get('/login', [UserController::class, 'login'])->name('loginadmin');
    Route::post('/loginproses', [UserController::class, 'proseslogin'])->name('loginproses');
});

Route::middleware(LoggedIn::class)->group(function () {

    Route::post('/prosesregister', [UserController::class, 'daftar'])->name('prosesregister');
    Route::post('/logout', [UserController::class, 'logout'])->name('logout');
    Route::get('/admin/dashboard', [UserController::class, 'dashboard'])->name('dashboardadmin');

    // leaderboard
    Route::get('/leaderboard', [quizController::class, 'leaderboard'])->name('leaderboard');

    //User
    Route::get('/dataSiswa', [UserController::class, 'tampilData'])->name('dataSiswa');
    Route::get('/tambahSiswa', [UserController::class, 'tambahUser'])->name('tambahSiswa');
    Route::get('/edituser/{id}', [UserController::class, 'editUser'])->name('useredit');
    Route::put('/updateuser/{id}', [UserController::class, 'updateUser'])->name('updateuser');
    Route::delete('/userdelete/{id}', [UserController::class, 'deleteUser'])->name('userdelete');

    //materi
    Route::get('/materi', [MateriController::class, 'index'])->name('materi');
    Route::get('/tambahMateri', [MateriController::class, 'create'])->name('tambahMateri');
    Route::post('/prosestambah', [MateriController::class, 'store'])->name('prosestambah');
    Route::get('/editMateri/{id}', [MateriController::class, 'edit'])->name('materiedit');
    Route::put('/updateMateri/{id}', [MateriController::class, 'update'])->name('updatemateri');
    Route::delete('/materidelete/{id}', [MateriController::class, 'destroy'])->name('materidelete');

    //kuis
    Route::get('/kuis', [quizController::class, 'index'])->name('kuis');
    Route::get('/tambahKuis', [quizController::class, 'create'])->name('tambahkuis');
    Route::post('/prosestambahkuis', [quizController::class, 'store'])->name('prosestambahkuis');
    Route::get('/editKuis/{id}', [quizController::class, 'edit'])->name('kuisedit');
    Route::post('/updateKuis/{id}', [quizController::class, 'update'])->name('updatekuis');
    Route::delete('/kuisdelete/{id}', [quizController::class, 'destroy'])->name('kuisdelete');

    // Route hasil kuis (riwayat)
    Route::get('/hasilkuis', [quizController::class, 'daftarHasilKuisTerbaru'])->name('hasilkuis.index');

// Route BARU untuk mengunduh PDF
Route::get('/hasil-kuis/pdf', [quizController::class, 'downloadPDF'])->name('hasilkuis.pdf');

    // ===================================================================
    // == RUTE BARU UNTUK HAPUS HASIL KUIS ==
    // ===================================================================
    Route::delete('/hasilkuis/{siswa_id}', [quizController::class, 'destroyHasilKuis'])->name('hasilkuis.destroy');
    Route::delete('/hasilkuis-destroy-all', [quizController::class, 'destroyAllHasilKuis'])->name('hasilkuis.destroyAll');
    // ===================================================================

    // Jika ingin route untuk leaderboard (sudah ada)
    Route::get('/leaderboard', [quizController::class, 'leaderboard'])->name('leaderboard');
    Route::get('hasilkuis/history', [quizController::class, 'historyHasilKuis'])->name('hasilkuis.history');
    Route::get('hasilkuis/history/pdf', [App\Http\Controllers\quizController::class, 'downloadHistoryPDF'])
    ->name('hasilkuis.history.pdf');
      Route::post('history/destroy-batch', [quizController::class, 'destroyBatch'])->name('hasilkuis.history.destroyBatch');

    // Hapus semua history hasil kuis
    Route::delete('history/destroy-all', [quizController::class, 'destroyAll'])->name('hasilkuis.history.destroyAll');


    // Profile routes
    Route::get('/profile/edit', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::put('/profile/update', [ProfileController::class, 'update'])->name('profile.update');


});
