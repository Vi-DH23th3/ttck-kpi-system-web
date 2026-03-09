<?php

use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\UserController;
use App\Http\Controllers\DonViController;
use App\Http\Controllers\ChucVuController;
use App\Http\Controllers\QlCongViecController;
use App\Http\Controllers\DMCongViecController;
use App\Models\DanhMucCongViec;

// Route::get('/', function () {
//     return view('welcome');
// });


Route::middleware('auth')->group(function () {
    Route::get('/', function () {
        return view('dashboard');
    })->middleware(['auth', 'verified'])->name('dashboard');

    Route::get('/profile', [ProfileController::class, 'index'])->name('profile.index');
    Route::put('/profile', [ProfileController::class, 'update'])->name('profile.update');
    // Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    //Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    Route::resource('users', UserController::class);
    Route::post('/users/import', [UserController::class, 'import'])->name('users.import');

    Route::resource('donvi', DonViController::class);
    Route::resource('chucvu', ChucVuController::class);
    Route::resource('/qlcongviec/dmcongviec', DMCongViecController::class);

    Route::get('/qlcongviec', [QlCongViecController::class, 'index'])->name('qlcongviec.index');
    // Route::get('/qlcongviec/dmcongviec', [QlCongViecController::class, 'dmcongviec'])->name('qlcongviec.dmcongviec');
    // Route::get('/qlcongviec/dmcongviec/{id}/edit', [QlCongViecController::class, 'edit'])->name('qlcongviec.dmcongviec.edit');
    Route::get('/giaochitieu', [QlCongViecController::class, 'giaoChiTieu'])->name('qlcongviec.giaochitieu');
    Route::post('/giaochitieu', [QlCongViecController::class, 'xuLyGiaoViec'])->name('qlcongviec.giaokpi');
    Route::get('/qlcongviec/thuvienkpi', [QlCongViecController::class, 'thuvienkpi'])->name('qlcongviec.thuvienkpi');
    Route::post('/qlcongviec/thuvienkpi', [QlCongViecController::class, 'themThuVienKPI'])->name('qlcongviec.thuvienkpi.create');
});

require __DIR__.'/auth.php';
