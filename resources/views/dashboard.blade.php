@extends('layouts.admin')
@section('title', 'Dashboard')
@section('content')
    <div class="min-h-screen bg-[#F8F9FA] dark:bg-[#0a0a0a]">
    <div class="container mx-auto px-4 py-6">
        
        <div class="flex flex-col md:flex-row justify-between items-start md:items-center mb-8 gap-4">
            <div>
                <h1 class="text-2xl font-bold text-[#1b1b18] dark:text-white">Bảng điều khiển KPI</h1>
                <p class="text-sm text-[#706f6c]">Học kỳ II - Năm học 2025-2026</p>
            </div>
            <div class="flex gap-2">
                <select class="rounded-lg border-[#e3e3e0] text-sm dark:bg-[#161615] dark:text-white">
                    <option>Tháng này</option>
                    <option>Quý này</option>
                </select>
                <button class="bg-[#F53003] hover:bg-orange-600 text-dark px-4 py-2 rounded-lg text-sm font-medium transition-all">
                    <i class="bi bi-plus-circle me-2"></i>Thêm báo cáo
                </button>
            </div>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4 mb-8">
            <div class="bg-white dark:bg-[#161615] p-5 rounded-xl shadow-sm border border-[#e3e3e0] dark:border-[#3E3E3A]">
                <div class="flex items-center gap-3 mb-2">
                    <div class="w-10 h-10 bg-blue-100 text-blue-600 rounded-lg flex items-center justify-center">
                        <i class="bi bi-book fs-5"></i>
                    </div>
                    <span class="text-sm text-[#706f6c]">Lớp học mới</span>
                </div>
                <div class="flex items-baseline gap-2">
                    <span class="text-2xl font-bold">24</span>
                    <span class="text-xs text-green-500 font-bold">+15%</span>
                </div>
            </div>

            <div class="bg-white dark:bg-[#161615] p-5 rounded-xl shadow-sm border border-[#e3e3e0] dark:border-[#3E3E3A]">
                <div class="flex items-center gap-3 mb-2">
                    <div class="w-10 h-10 bg-orange-100 text-orange-600 rounded-lg flex items-center justify-center">
                        <i class="bi bi-person-badge fs-5"></i>
                    </div>
                    <span class="text-sm text-[#706f6c]">Giảng viên online</span>
                </div>
                <div class="flex items-baseline gap-2">
                    <span class="text-2xl font-bold">12</span>
                    <span class="text-xs text-[#706f6c]">Ổn định</span>
                </div>
            </div>

            <div class="bg-white dark:bg-[#161615] p-5 rounded-xl shadow-sm border border-[#e3e3e0] dark:border-[#3E3E3A]">
                <div class="flex items-center gap-3 mb-2">
                    <div class="w-10 h-10 bg-green-100 text-green-600 rounded-lg flex items-center justify-center">
                        <i class="bi bi-check-all fs-5"></i>
                    </div>
                    <span class="text-sm text-[#706f6c]">KPI Hoàn thành</span>
                </div>
                <div class="flex items-baseline gap-2">
                    <span class="text-2xl font-bold">78%</span>
                    <div class="w-full bg-gray-200 h-1 rounded-full ml-2">
                        <div class="bg-green-500 h-1 rounded-full" style="width: 78%"></div>
                    </div>
                </div>
            </div>

            <div class="bg-white dark:bg-[#161615] p-5 rounded-xl shadow-sm border border-[#e3e3e0] dark:border-[#3E3E3A]">
                <div class="flex items-center gap-3 mb-2">
                    <div class="w-10 h-10 bg-red-100 text-red-600 rounded-lg flex items-center justify-center">
                        <i class="bi bi-exclamation-octagon fs-5"></i>
                    </div>
                    <span class="text-sm text-[#706f6c]">Việc sắp hết hạn</span>
                </div>
                <div class="flex items-baseline gap-2">
                    <span class="text-2xl font-bold text-red-500">03</span>
                </div>
            </div>
        </div>

        <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
            
            <div class="lg:col-span-2 space-y-6">
                <div class="bg-white dark:bg-[#161615] rounded-xl border border-[#e3e3e0] dark:border-[#3E3E3A] overflow-hidden shadow-sm">
                    <div class="p-5 border-b border-[#f3f3f1] dark:border-[#3E3E3A] flex justify-between items-center">
                        <h3 class="font-bold">Nhiệm vụ KPI trọng tâm</h3>
                        <button class="text-xs text-blue-600">Xem tất cả</button>
                    </div>
                    <div class="overflow-x-auto">
                        <table class="w-full text-left text-sm">
                            <thead class="bg-[#fcfcfc] dark:bg-[#1b1b18] text-[#706f6c]">
                                <tr>
                                    <th class="p-4 font-semibold">Tên chỉ tiêu</th>
                                    <th class="p-4 font-semibold">Mục tiêu</th>
                                    <th class="p-4 font-semibold text-center">Tiến độ</th>
                                    <th class="p-4 font-semibold">Hạn chót</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr class="border-b border-[#f3f3f1] dark:border-[#3E3E3A] hover:bg-gray-50 transition-colors">
                                    <td class="p-4 font-medium">Lớp IELTS 6.5 mới</td>
                                    <td class="p-4">10 lớp</td>
                                    <td class="p-4">
                                        <div class="flex items-center gap-2">
                                            <div class="flex-1 bg-gray-200 h-1.5 rounded-full">
                                                <div class="bg-blue-500 h-1.5 rounded-full" style="width: 60%"></div>
                                            </div>
                                            <span class="text-[11px]">60%</span>
                                        </div>
                                    </td>
                                    <td class="p-4 text-xs">31/12/2026</td>
                                </tr>
                                <tr class="hover:bg-gray-50 transition-colors">
                                    <td class="p-4 font-medium">Chứng chỉ MOS học viên</td>
                                    <td class="p-4">500 CC</td>
                                    <td class="p-4">
                                        <div class="flex items-center gap-2">
                                            <div class="flex-1 bg-gray-200 h-1.5 rounded-full">
                                                <div class="bg-green-500 h-1.5 rounded-full" style="width: 100%"></div>
                                            </div>
                                            <span class="text-[11px]">100%</span>
                                        </div>
                                    </td>
                                    <td class="p-4 text-xs">Hoàn thành</td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>

            <div class="space-y-6">
                <div class="bg-white dark:bg-[#161615] p-6 rounded-xl border border-[#e3e3e0] dark:border-[#3E3E3A] shadow-sm text-center">
                    <h3 class="font-bold mb-4">Tổng thể mục tiêu</h3>
                    <div class="relative inline-flex items-center justify-center">
                        <svg class="w-32 h-32">
                            <circle class="text-gray-200" stroke-width="8" stroke="currentColor" fill="transparent" r="50" cx="64" cy="64"/>
                            <circle class="text-[#F53003]" stroke-width="8" stroke-dasharray="314.15" stroke-dashoffset="62.8" stroke-linecap="round" stroke="currentColor" fill="transparent" r="50" cx="64" cy="64"/>
                        </svg>
                        <span class="absolute text-xl font-bold">80%</span>
                    </div>
                    <p class="text-xs text-[#706f6c] mt-4 italic">"Bạn đang đi đúng hướng!"</p>
                </div>

                <div class="bg-white dark:bg-[#161615] p-5 rounded-xl border border-[#e3e3e0] dark:border-[#3E3E3A] shadow-sm">
                    <h3 class="font-bold mb-4">Ghi chú nhanh</h3>
                    <ul class="space-y-3">
                        <li class="flex gap-3 text-sm">
                            <input type="checkbox" class="mt-1 rounded text-[#F53003]">
                            <span>Duyệt danh sách thi Tin học văn phòng</span>
                        </li>
                        <li class="flex gap-3 text-sm">
                            <input type="checkbox" class="mt-1 rounded text-[#F53003]">
                            <span>Gửi báo cáo KPI Quý cho Ban Giám đốc</span>
                        </li>
                    </ul>
                </div>
            </div>

        </div>
    </div>
</div>
@endsection