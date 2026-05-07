<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Admin\UserController;
use App\Http\Controllers\Admin\DonViController;
use App\Http\Controllers\Admin\ChucVuController;
use App\Http\Controllers\Admin\NamHocController;

use App\Http\Controllers\Staff\BaoCaoCongViecController;

use App\Http\Controllers\Manager\GiaoKPIController;
use App\Http\Controllers\Manager\QlCongViecController;
use App\Http\Controllers\Manager\PhanCongController;

use App\Http\Controllers\Common\DashboardController;
use App\Http\Controllers\Common\NotificationController;
use App\Http\Controllers\Common\PasswordChangeController;
use App\Http\Controllers\Common\ProfileController;
use App\Http\Controllers\Common\XemLichSuBaoCaoController;

use App\Http\Controllers\System\ThuVienKPIController;
use App\Http\Controllers\System\DMCongViecController;


Route::middleware(['auth', 'check.info'])->group(function () {
    Route::post('/bosungtt/{id}', [ProfileController::class, 'boSungThongTinPost'])->name('bosungtt.post');

    Route::get('/', [DashboardController::class, 'index'])->name('dashboard');
    Route::post('/', [QlCongViecController::class, 'export'])->name('export');

    Route::get('/profile', [ProfileController::class, 'index'])->name('profile.index');
    Route::put('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::get('/bosungtt', [ProfileController::class, 'boSungThongTin'])->name('bosungtt');
    Route::get('/qlcongviec/xemlsbaocao/{id}', [XemLichSuBaoCaoController::class, 'xemLichSuBaoCao'])->name('qlcongviec.xemlsbaocao');
    // Route::post('/profile/storebaocao', [BaoCaoCongViecController::class, 'storeBaoCao'])->name('profile.storebaocao');

    Route::get('/notifications/{id}/read', [NotificationController::class, 'read'])->name('notifications.read');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

});
Route::prefix('system')->name('system.')->middleware(['auth', 'role:admin,manager'])->group(function () {

    Route::get('/qlcongviec/thuvienkpi', [ThuVienKPIController::class, 'thuvienkpi'])->name('qlcongviec.thuvienkpi');
    Route::post('/qlcongviec/thuvienkpi', [ThuVienKPIController::class, 'themThuVienKPI'])->name('qlcongviec.thuvienkpi.create');

    Route::resource('qlcongviec/dmcongviec', DMCongViecController::class);
    Route::get('/qlcongviec', [QlCongViecController::class, 'index'])->name('qlcongviec.index');
    // Route::get('/qlcongviec/qltiendo', [QlCongViecController::class, 'index'])->name('qlcongviec.qltiendo');
});
Route::prefix('admin')->name('admin.') ->middleware(['auth', 'role:admin'])->group(function () {
    Route::resource('users', UserController::class);
    Route::post('users/{id}/reset', [PasswordChangeController::class, 'reset'])->name('users.resetpass');
    Route::post('users/import', [UserController::class, 'import'])->name('users.import');
    Route::patch('users/{id}/unlock', [UserController::class, 'unlock'])->name('users.unlock');

    Route::resource('donvi', DonViController::class);
    Route::resource('chucvu', ChucVuController::class);
    Route::resource('namhoc', NamHocController::class);
    // Route::resource('qlcongviec/dmcongviec', DMCongViecController::class);
});

Route::prefix('manager')->name('manager.')->middleware(['auth', 'role:manager'])->group(function() {
    Route::get('/donvi/{id}', [DonViController::class, 'show'])->name('donvi.show');

    Route::get('/giaochitieu', [GiaoKPIController::class, 'giaoChiTieu'])->name('qlcongviec.giaochitieu');
    Route::post('/giaochitieu/importfile', [GiaoKPIController::class, 'importFile'])->name('qlcongviec.giaokpi.import');
    Route::post('/giaochitieu', [GiaoKPIController::class, 'xuLyGiaoViec'])->name('qlcongviec.giaokpi');

    Route::get('/giaochitieu/{index}', [GiaoKPIController::class, 'getImportRow']);

    Route::get('/phancong', [PhanCongController::class, 'index'])->name('phancong');
    Route::get('/phancong/edit/{id}', [PhanCongController::class, 'edit'])->name('phancong.edit');
    Route::put('/phancong/edit/{id}', [PhanCongController::class, 'update'])->name('phancong.update');
    Route::delete('/phancong/destroy/{id}', [PhanCongController::class, 'destroy'])->name('phancong.destroy');

    Route::get('/qlcongviec/qltiendo/{id}', [QlCongViecController::class, 'xemBaoCao'])->name('qlcongviec.qltiendo.xembc');
    Route::post('/qlcongviec/qltiendo/duyet', [QlCongViecController::class, 'duyetBaoCao'])->name('qlcongviec.qltiendo.duyet');
    Route::post('/qlcongviec/qltiendo/tralai', [QlCongViecController::class, 'traLaiBaoCao'])->name('qlcongviec.qltiendo.tralai');
});
    
Route::prefix('staff')->name('staff.')->middleware(['auth', 'role:staff'])->group(function() {
    Route::post('/profile/storebaocao', [BaoCaoCongViecController::class, 'storeBaoCao'])->name('profile.storebaocao');
});
    
   
require __DIR__.'/auth.php';
