<?php

use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\UserController;
use App\Http\Controllers\DonViController;
use App\Http\Controllers\ChucVuController;
use App\Http\Controllers\QlCongViecController;
use App\Http\Controllers\DMCongViecController;
use App\Http\Controllers\BaoCaoCongViecController;
use App\Http\Controllers\GiaoKPIController;
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
   
    Route::get('/giaochitieu', [GiaoKPIController::class, 'giaoChiTieu'])->name('qlcongviec.giaochitieu');
    Route::post('/giaochitieu', [GiaoKPIController::class, 'xuLyGiaoViec'])->name('qlcongviec.giaokpi');
    Route::get('/giaokpiimport', [GiaoKPIController::class, 'giaoKPIImport'])->name('qlcongviec.giaokpiimport.index');
    Route::post('/giaokpiimport', [GiaoKPIController::class, 'xuLyGiaoKPIImport'])->name('qlcongviec.giaokpiimport');
    Route::post('/storegiaokpiimport', [GiaoKPIController::class, 'storeGiaoChiTieuImport'])->name('qlcongviec.giaokpiimport.store');
    Route::get('/qlcongviec/thuvienkpi', [QlCongViecController::class, 'thuvienkpi'])->name('qlcongviec.thuvienkpi');
    Route::post('/qlcongviec/thuvienkpi', [QlCongViecController::class, 'themThuVienKPI'])->name('qlcongviec.thuvienkpi.create');
    Route::post('/qlcongviec/qltiendo', [QlCongViecController::class, 'index'])->name('qlcongviec.qltiendo');
    Route::post('/qlcongviec/qltiendo/duyet', [QlCongViecController::class, 'duyetBaoCao'])->name('qlcongviec.qltiendo.duyet');
    Route::post('/qlcongviec/qltiendo/tralai', [QlCongViecController::class, 'traLaiBaoCao'])->name('qlcongviec.qltiendo.tralai');

    Route::post('/profile/storebaocao', [BaoCaoCongViecController::class, 'storeBaoCao'])->name('profile.storebaocao');
});

require __DIR__.'/auth.php';
