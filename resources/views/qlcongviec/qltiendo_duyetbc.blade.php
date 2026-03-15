@extends('layouts.admin')
@section('title', 'Quản lý tiến độ & Duyệt báo cáo')
@section('content')
<div class="card-body fs-6">
    <div class="table-responsive border rounded">
        <div class="p-3 border bg-light shadow-sm p-4 rounded-2">
        <h5 class="card-header fw-semibold">Danh sách công việc</h5>
        <div class="table-responsive mb-3 fw-semibold border rounded">
            <table class="table datatable-project table-hover">
            <thead class="table-light">
                <tr>
                    <th>STT</th>
                    <th>KPI / Công việc</th>
                    <th>Nhân viên</th>
                    <th>Tiến độ hiện tại</th>
                    <th>Báo cáo chờ duyệt</th>
                    <th>Tiến độ dự kiến</th>
                    <th>Thao tác</th>
                </tr>
            </thead>
            <tbody>
                @foreach($dsPhanCong as $dspc)
                    @php
                        $chitieu = $dspc->thuVienKPI->chi_tieu ?? 0;
                        $daDuyet = $dspc->thuc_te_dat_duoc ?? 0;
                        $choDuyet = $dspc->baoCaoCongViec->where('trangthai_duyet', 'chua_duyet')->sum('tien_do_thuc');
                        $thuc_te_dat_duoc = $chitieu > 0 ? ($daDuyet / $chitieu) * 100 : 0;
                        $tiendodukien = $chitieu > 0 ? (($daDuyet + $choDuyet) / $chitieu) * 100 : 0;

                        $tt_chuaduyet = $dspc->baoCaoCongViec->where('trangthai_duyet', 'chua_duyet');
                        $tt_daduyet = $dspc->baoCaoCongViec->where('trangthai_duyet', 'da_duyet');
                        $tt_tralai = $dspc->baoCaoCongViec->where('trangthai_duyet', 'tra_lai');
                    @endphp
                    <tr>
                        <td>{{ $dspc->id }}</td>
                        <td>{{ $dspc->thuVienKPI->ten_kpi }}</td>
                        <td>{{ $dspc->nguoiDuocGiao->name }}</td>
                        <td>
                            <div class="progress" style="height: 10px;">
                                <div class="progress-bar bg-success" style="width: {{ $thuc_te_dat_duoc }}%"></div>
                            </div>
                            <small> {{ $dspc->thuc_te_dat_duoc }}/{{ $dspc->thuVienKPI->chi_tieu }}</small>
                                
                        </td>
                        <td> 


                            @if($tt_chuaduyet->isNotEmpty())
                                <button class="btn btn-xs btn-outline-primary btn-xem-bao-cao" >Xem báo cáo</button>
                                <div class="d-none" id="file_minh_chung">
                                    @foreach($dspc->baoCaoCongViec as $bc)
                                    <br>
                                        <ul class="list-unstyled mb-0">
                                            <li class="mb-2 pb-2 border-bottom">
                                                <a href="{{ asset('storage/' . $bc->file_minh_chung) }}" 
                                                target="_blank" 
                                                class="btn btn-xs btn-outline-info">
                                                <i class="fas fa-eye"></i> {{$bc->file_minh_chung}}
                                                </a>

                                                <a href="{{ asset('storage/' . $bc->file_minh_chung) }}" 
                                                download 
                                                class="btn btn-xs btn-outline-primary">
                                                <i class="fas fa-download"></i> Tải
                                                </a>
                                            </li>   
                                        </ul>
                                        <textarea class="form-control" rows="3">{{ $bc->ghi_chu ?? ' '}}</textarea>
                                    @endforeach
                                </div> 
                            @else
                                <span class="text-muted small">Không có file mới</span>
                            @endif
                        </td>
                        <td>
                            <div class="progress" style="height: 10px; border: 1px dashed #ccc;">
                                <div class="progress-bar bg-info" style="width: {{ $tiendodukien }}%"></div>
                            </div>
                            <small class="text-info fw-bold">{{ $tiendodukien }}%</small>
                            </td>
                        <td>
                            @if($tt_chuaduyet->isNotEmpty())
                            <form action="{{route('qlcongviec.qltiendo.duyet')}}" method="POST">
                                @csrf
                                <input type="hidden" name="bao_cao_cong_viec_id" value="{{ $dspc->baoCaoCongViec->where('trangthai_duyet', 'chua_duyet')->first()->id }}">
                                <button type="submit" class="btn btn-primary">
                                    Duyệt
                                </button>
                            </form>    
                            <button type="button" class="btn btn-danger btn-tralaibc" data-tlid = "{{ $dspc->baoCaoCongViec->where('trangthai_duyet', 'chua_duyet')->first()->id }}">
                                Trả lại
                            </button>
                            @elseif($tt_daduyet->isNotEmpty() && $tt_daduyet->count() < $dspc->thuVienKPI->chi_tieu)
                            <span class="text-success trang_thai"><i>Đã duyệt</i></span>
                             @elseif($dspc->thuc_te_dat_duoc == $dspc->thuVienKPI->chi_tieu)
                            <span class="text-success trang_thai"><i>Đã hoàn thành</i></span>
                            @elseif($tt_tralai->isNotEmpty())
                            <span class="text-warning trang_thai"><i>Đã trả lại</i></span>
                            @else
                            <span class="text-muted trang_thai"><i>Không có báo cáo</i></span>
                            @endif
                        </td>
                    </tr>
                @endforeach
            </tbody>
            </table>
        </div>
        </div>
    </div>
</div>     
    
<div class="modal fade" id="modalXemBaoCao" tabindex="-1" aria-labelledby="modalXemBaoCaoLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header bg-dark text-white">
                <h5 class="modal-title" id="modalXemBaoCaoLabel"><i class="bi bi-file-earmark-arrow-up me-2"></i>Xem Báo Cáo</h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body body-xem-bao-cao">
            </div>
            <div class="modal-footer bg-light">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Hủy</button>
            </div>
        </div>
    </div>
</div>  
<div class="modal fade" id="modalTraLai" tabindex="-1" aria-labelledby="modalTraLaiLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header bg-dark text-white">
                <h5 class="modal-title" id="modalTraLaiLabel"><i class="bi bi-file-earmark-arrow-up me-2"></i>Trả lại</h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body body-tra-lai">
                <div class="mb-3">
                    <label for="ghi_chu_tl" class="form-label">Lý do trả lại</label>
                </div>
                <form action="{{route('qlcongviec.qltiendo.tralai')}}" method="POST">
                    @csrf
                    <input type="hidden" name="bao_cao_cong_viec_id" class="tlbc">
                    <textarea class="form-control" rows="3" name="ghi_chu_tl"></textarea>
                    <button type="submit" class="btn btn-danger" >
                    Trả lại
                    </button>
                </form>
            </div>
            <div class="modal-footer bg-light">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Hủy</button>
            </div>
        </div>
    </div>
</div> 
@push('script')
<script src="{{ asset('js/baocao.js') }}"></script>                     
@endpush
@endsection