<div class="tab-pane fade" id="project" role="tabpanel">
        <div class="row g-4"> 
            @foreach($dscongviec as $cv)
                <div class="col-xl-4 col-lg-6 col-md-6">
                    <div class="card h-100 shadow-sm border-0 mt-3">
                        <div class="card-header bg-transparent border-0 pb-0">
                            <h5 class="mb-1 text-primary text-uppercase">{{ $cv->phanCong->thuVienKPI->ten_kpi }}</h5> 
                            <div class="small text-muted">
                                <span class="fw-semibold">Người giao:</span> {{ $cv->nguoipc }}
                            </div>
                            @php
                                $bg_MacDinh = 'success';
                                if (str_contains($cv->canh_bao, 'Chưa đạt chu kỳ') || str_contains($cv->canh_bao, 'Chưa đạt đa chỉ tiêu') ){
                                    $bg_MacDinh = 'danger';
                                } elseif (str_contains($cv->canh_bao, 'Thiếu chu kỳ') || str_contains($cv->canh_bao, 'Sắp hết hạn') ||  str_contains($cv->canh_bao, 'ngưỡng bù')) {
                                    $bg_MacDinh = 'warning text-dark';
                                } elseif (str_contains($cv->canh_bao, 'Chưa có dữ liệu') || str_contains($cv->canh_bao, 'Chưa bắt đầu')) {
                                    $bg_MacDinh = 'secondary';
                                }elseif(str_contains($cv->canh_bao, 'Đang đúng tiến độ') )
                                    $bg_MacDinh = 'info';
                            @endphp

                            @if($cv->canh_bao)
                                <div class="badge bg-{{ $bg_MacDinh }} w-auto text-wrap p-2 shadow-sm" style="font-size: 0.7rem;">
                                     {{ $cv->canh_bao }}
                                </div>
                            @endif
                        </div>

                        <div class="card-body">
                            <div class="bg-light p-3 rounded-3 mb-3">
                                <div class="d-flex justify-content-start align-items-center mb-2 flex-column">
                                    <span class="small text-muted"><strong>Chỉ tiêu:</strong> {{$cv->phanCong->thuVienKPI->chi_tieu}} {{$cv->phanCong->thuVienKPI->don_vi}}</span>
                                    <div>
                                        <span class="text-muted"><strong>Bắt đầu:</strong> {{ date('d/m/Y', strtotime($cv->phanCong->ngay_bat_dau)) }}</span>
                                        <span class="text-muted"><strong>Kết thúc:</strong> {{ date('d/m/Y', strtotime($cv->phanCong->ngay_ket_thuc)) }}</span>
                                    </div>
                                    
                                </div>
                                @if(!empty($cv->phanCong->dieu_kien_phu))
                                    @php
                                        $dieuKien = is_array($cv->dieu_kien_phu)
                                            ? $cv->dieu_kien_phu
                                            : json_decode($cv->dieu_kien_phu, true) ?? [];
                                    @endphp
                                    <span class="text-muted fw-bold">Điều kiện phụ:</span> <br>
                                        
                                        @foreach($dieuKien as $dk)
                                        <div style="font-size: 0.85rem;">                 
                                            <span> {{ $dk['pham_vi'] == 'bao_cao' ? 'Mỗi báo cáo' : 'Lũy kế' }}:</span>
                                            <span><i class="bi bi-dot"></i> {{ $dk['ten'] ?? 'N/A' }}</span>
                                            <small class="text-primary">{{ $dk['toan_tu'] ?? '=' }}</small>
                                            <span class="fw-bold">{{ number_format($dk['gia_tri'] ?? 0) }}</span> 
                                            <span> {{ $dk['don_vi'] }} / {{ $dk['chu_ky'] }} </span>         
                                        </div>
                                        @endforeach
                                    
                                    @endif
                                    @if($cv->phanCong->so_lan_toi_thieu_thang)
                                        <span class="small">
                                            <i class="bi bi-activity"></i>
                                            Tần suất: {{ $cv->phanCong->so_lan_toi_thieu_thang }} lần / {{ $cv->phanCong->chu_ky_thang }} tháng
                                        </span>
                                    @endif
                                
                            </div>

                            <div class="small mb-1">
                                Ưu tiên: 
                                <span class="fw-bold {{ $cv->phanCong->muc_do_uu_tien == 3 ? 'text-danger' : ($cv->phanCong->muc_do_uu_tien == 2 ? 'text-warning' : 'text-info') }}">
                                    {{ $cv->phanCong->muc_do_uu_tien == 1 ? 'Thấp' : ($cv->phanCong->muc_do_uu_tien == 2 ? 'Trung bình' : 'Cao') }}
                                </span>
                            </div>
                            <p class="text-muted small text-truncate-2" style="height: 2.5rem;">{{ $cv->phanCong->ghi_chu ?? 'Không có ghi chú.' }}</p>
                        </div>

                        <div class="card-footer bg-transparent border-top pt-3">
                            <div class="d-flex justify-content-between align-items-center mb-2">
                                <span class="badge bg-label-warning text-warning small border border-warning px-2">
                                    <i class="bi bi-clock me-1"></i> Còn {{ round($cv->ngayconlai, 1) }} ngày
                                </span>
                                <span class="fw-bold text-primary">{{ $cv->tiendo ?? 0 }}%</span>
                            </div>
                            
                            <div class="progress mb-3" style="height: 6px;">
                                <div class="progress-bar progress-bar-striped progress-bar-animated" role="progressbar" style="width: {{ $cv->tiendo }}%;"></div>
                            </div>

                            <div class="d-flex justify-content-between align-items-center gap-2">
                                <button class="btn btn-sm btn-primary btn-baocao"
                                        data-id="{{ $cv->id }}" 
                                        data-ten="{{ $cv->phanCong->thuVienKPI->ten_kpi ?? ''}}"
                                        data-chitieu="{{ $cv->phanCong->thuVienKPI->chi_tieu ?? ''}}"
                                        data-loaikpi="{{ $cv->phanCong->loai_kpi }}"
                                        data-dieukien='@json($cv->phanCong->dieu_kien_phu)'
                                        data-donvi = "{{ $cv->phanCong->thuVienKPI->don_vi ?? ''}}"
                                        data-chuky = "{{ $cv->phanCong->thuVienKPI->chu_ky ?? ''}}">
                                    Báo cáo
                                </button>
                                <form action="{{route('qlcongviec.xemlsbaocao',  $cv->id )}}" method="GET">
                                    <button type="submit" class="btn btn-sm btn-outline-primary" >Xem lịch sử</button>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>
    </div>