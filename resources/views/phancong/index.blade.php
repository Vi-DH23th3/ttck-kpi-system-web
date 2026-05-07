@extends('layouts.admin')
@section('title', 'Giao KPI')

@section('content')
<div class="container-fluid mt-4">
    <div class="card shadow">
        <div class="card-header bg-primary text-white d-flex justify-content-between align-items-center">
                <h5 class="mb-0">Danh Sách Phân Công Công Việc</h5>
                <form id="mainForm" action="{{ route('manager.phancong') }}" method="GET" class="row align-items-end g-3">
                    @csrf
                    <select name="filter_nh" class="form-select border-2" onchange="this.form.submit()">
                        @foreach($namhoc as $nh)
                            <option value="{{$nh->id}}" {{ request('filter_nh') == $nh->id ? 'selected' : '' }}>{{$nh->ten_nam_hoc}}</option>
                        @endforeach
                    </select>
                </form>
            

            <a class="btn btn-light btn-sm" href="{{route('manager.qlcongviec.giaochitieu')}}"><i class="bi bi-send-plus-fill me-1"></i> Giao chỉ tiêu mới</a>
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-bordered table-hover align-middle">
                    <thead class="table-light">
                        <tr>
                            <th>STT</th>
                            <th>Tên KPI</th>
                            <th>Người Được Giao</th>
                            <th>Chỉ tiêu</th>
                            <th>Chế Độ Bù</th>
                            <th>Thời Gian</th>
                            <th>Mức độ ưu tiên</th>
                            <th>Trạng Thái</th>
                            <th>Thao Tác</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($phanCong as $index => $item)
                            @php
                                $loai = '';
                                if($item->loai_kpi === 'don_gian'){
                                    $loai = 'Đơn giản';
                                }
                                elseif($item->loai_kpi === 'nang_cao'){
                                    $loai = 'KPI theo chu kì';
                                }
                                else{
                                    $loai = 'Đa chỉ tiêu';
                                }
                            @endphp 
                        <tr>
                            <td>{{ $index + 1 }}</td>
                            <td>
                                <strong>{{ $item->thuVienKPI->ten_kpi ?? 'N/A' }}</strong>
                                <br><small class="text-muted">Loại: {{ $loai }}</small>
                            </td>
                            <td>
                                @if($item->chiTietPhanCong->isNotEmpty())
                                    @foreach($item->chiTietPhanCong as $ct)
                                        <span class="badge bg-info text-dark">
                                            {{ $ct->nguoiDuocGiao->name ?? 'User #'.$ct->user_id }}
                                        </span>
                                    @endforeach
                                @else
                                    <span class="text-danger">Chưa giao ai</span>
                                @endif
                            </td>
                            <td>
                                @if($item->loai_kpi === 'da_chi_tieu')
                                    @if($item->dieu_kien_phu)
                                        <ul class="mb-0 ps-3 small">
                                            @foreach($item->dieu_kien_phu as $dk)
                                                <li>
                                                    <span> {{ $dk['pham_vi'] == 'bao_cao' ? 'Mỗi báo cáo' : 'Lũy kế' }}:</span>
                                                    <span><i class="bi bi-dot"></i> {{ $dk['ten'] ?? 'N/A' }}</span>
                                                    <small class="text-primary">{{ $dk['toan_tu'] ?? '=' }}</small>
                                                    <span class="fw-bold">{{ number_format($dk['gia_tri'] ?? 0) }}</span> 
                                                    <span> {{ $dk['don_vi'] }} / {{ $dk['chu_ky'] }} </span>   
                                                </li>
                                            @endforeach
                                        </ul>
                                    @else
                                        <span class="text-muted">-</span>
                                    @endif
                                @else
                                    <div>{{ $item->thuVienKPI->chi_tieu ?? 0 }} {{ $item->thuVienKPI->don_vi ?? ' ' }} / {{ $item->thuVienKPI->chu_ky ?? '' }}</div>
                                    @if($item->loai_kpi === 'nang_cao')
                                        <small class="text-secondary">Lặp lại: {{ $item->chu_ky_thang ?? 1 }} tháng/ {{ $item->so_lan_toi_thieu_thang ?? 0 }} lần</small>
                                    @endif
                                @endif
                                
                            </td>
                            <td>
                                @if($item->cho_phep_bu)
                                    <span class="text-success">✔ Cho phép</span><br>
                                    <small>(Ngưỡng: {{ $item->nguong_duoc_bu }}%)</small>
                                @else
                                    <span class="text-muted">Không</span>
                                @endif
                            </td>
                            <td class="small">
                                {{date('d/m/Y', strtotime($item->ngay_bat_dau)) }} <br>
                                {{date('d/m/Y', strtotime($item->ngay_ket_thuc)) }}
                            </td>
                            <td>
                                @php
                                    if($item->muc_do_uu_tien === 1){
                                        $mucDoUuTien = 'Thấp';
                                        $text = 'info';
                                    }     
                                    elseif ($item->muc_do_uu_tien === 2){
                                        $mucDoUuTien = 'Trung bình';
                                        $text = 'warning';
                                    }
                                    else{
                                        $mucDoUuTien = 'Cao';
                                        $text = 'danger';
                                    }
                                        
                                @endphp
                                <span class="text-{{$text}}">{{$mucDoUuTien}}</span>
                            </td>
                            <td>
                                @php
                                    $statusClass = [
                                        'chua_bat_dau' => 'bg-secondary',
                                        'dang_thuc_hien' => 'bg-primary',
                                        'da_hoan_thanh' => 'bg-success',
                                        'dang_no' => 'bg-warning'
                                    ];
                                    if($item->trang_thai === 'chua_bat_dau')
                                        $tt = 'Chưa thực hiện';
                                    elseif ($item->trang_thai === 'dang_thuc_hien')
                                        $tt = 'Đang thực hiện';
                                    elseif ($item->trang_thai === 'dang_no')
                                        $tt = 'Đang nợ';
                                    elseif ($item->trang_thai === 'da_hoan_thanh')
                                        $tt = 'Đã hoàn thành';
                                    else
                                        $tt = 'Chưa đạt'
                                @endphp
                                <span class="badge {{ $statusClass[$item->trang_thai] ?? 'bg-dark' }}">
                                    {{ ucfirst(str_replace('_', ' ', $tt)) }}
                                </span>
                            </td>
                            <td>
                                <div class="btn-group">
                                    
                                    @if($item->trang_thai == 'chua_bat_dau')
                                        <form action="{{route('manager.phancong.destroy', $item->id)}}" method="POST">
                                            @csrf
                                            @method('DELETE')
                                            <button type="button" class="btn btn-sm btn-outline-danger" title="Xóa" onclick="xacNhan(this)" data-message="Xác nhận xóa KPI đã phân công?"><i class="bi bi-trash"></i></button>
                                        </form>
                                    @endif
                                    @if($item->trang_thai == 'chua_bat_dau' || $item->trang_thai == 'dang_thuc_hien')
                                        <form action="{{route('manager.phancong.edit', $item->id)}}" method="GET">
                                            <button  class="btn btn-sm btn-outline-warning" title="Sửa" ><i class="bi bi-pencil-square"></i></button>
                                        </form>
                                    @endif
                                </div>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
@push('script')
<script src="{{ asset('js/thongbaoxacnhan.js') }}"></script>
@endpush
@endsection