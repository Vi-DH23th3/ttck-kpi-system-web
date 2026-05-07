<?php

use App\Models\ChiTietPhanCong;
use App\Services\Cot_loi\TrangThaiKPIService;
use Illuminate\Foundation\Inspiring;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Schedule;

Artisan::command('inspire', function () {
    $this->comment(Inspiring::quote());
})->purpose('Display an inspiring quote');
Schedule::call(function(){
    $servie = app(TrangThaiKPIService::class);
    ChiTietPhanCong::with('phanCong.thuVienKPI')->chunk(100, function ($ds) use($servie) {
        foreach($ds as $ct){
            $servie->luuTrangThai($ct);
        }
    });
})->everyMinute();