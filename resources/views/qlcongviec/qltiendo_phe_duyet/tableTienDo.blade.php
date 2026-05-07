<table class="table table-hover align-middle bg-white mb-0" style="font-size: 0.9rem;">
        <thead class="table-light">
            <tr class=" sticky-top" style="top: 0; z-index: 10;">
                <th class="text-primary text-center py-3" style="width: 50px;">STT</th>
                <th class="text-primary py-3" style="width: 25%;">KPI / Nhân viên</th>
                <!-- <th class="text-primary text-center py-3">Loại</th> -->
                <th class="text-primary text-center py-3" style="width: 20%;">Tiến độ KPI</th>
                <!-- <th class="text-primary py-3" style="width: 15%;">Tần suất (Tháng)</th> -->
                <th class="text-primary text-center py-3" style="width: 12%;">% Quỹ thời gian</th>
                <th class="text-primary text-center py-3">Đánh giá</th>
                <th class="text-primary py-3">Hạn chót</th>
                <th class="text-primary text-center py-3">Trạng thái</th>
                <th class="text-primary text-center py-3">Cảnh báo</th>
                <th class="text-primary text-center py-3">Hành động</th>
            </tr>
        </thead>
        <tbody>
            @foreach($group as $pcid => $list)
            @php
                $loai = '';
                if($list['loai_kpi'] === 'don_gian'){
                    $loai = 'Đơn giản';
                }
                elseif($list['loai_kpi'] === 'nang_cao'){
                    $loai = 'KPI theo chu kì';
                }
                else{
                    $loai = 'Đa chỉ tiêu';
                } 
            @endphp
            <tr class="table-info border-bottom">
                <td colspan="10" class="py-2 px-3">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <strong>{{$pcid}} {{ $list['ten_kpi'] }}</strong>
                            <span class="badge {{ $list['loai_kpi'] == 'don_gian' ? 'bg-secondary' : 'bg-info' }}">
                                {{ $loai }} 
                            </span>
                            @if($list['so_lan_toi_thieu_thang'])
                                <div class="small text-muted mt-1">
                                    <i class="bi bi-activity"></i>
                                    {{ $list['so_lan_toi_thieu_thang'] }} lần / {{ $list['chu_ky_thang'] }} tháng
                                </div>
                            @endif
                        </div>
                        <div class="text-end">
                            <small class="fw-bold">
                                {{ $list['tien_do_trung_binh'] }}%
                            </small>
                        </div>
                    </div>
                </td>
            </tr>
            @if(!empty($list['dieu_kien_phu']))
            @php 
                $dieuKien = is_array($list['dieu_kien_phu']) ? $list['dieu_kien_phu'] : json_decode($list['dieu_kien_phu'], true) ?? []; 
            @endphp
            <tr class="border-bottom">
                <td colspan="10" class="px-4 py-2">
                    <div class="d-flex flex-wrap gap-2">
                        @foreach($dieuKien as $dk)
                            <span class="badge bg-light text-dark border px-2 py-1">
                                {{ $dk['ten'] }} 
                                <span class="text-primary">
                                    {{ $dk['toan_tu'] }}
                                </span>
                                <strong>{{ number_format($dk['gia_tri']) }}</strong>
                                <span> {{ $dk['don_vi'] }} / {{ $dk['chu_ky'] }} </span>
                                <small class="text-muted">
                                    ({{ $dk['pham_vi'] == 'tat_ca' ? 'Lũy kế' : 'Báo cáo' }})
                                </small>
                            </span>
                        @endforeach
                    </div>
                </td>
            </tr>
            @endif
    
            @foreach($list['chi_tiet'] as $index => $dspc)
            <tr>
                <td class="text-center text-muted"></td>
                
                 <!-- KPI & Nhân viên -->
                <td>
                    <!-- <div class="fw-bold text-dark mb-1">{{ $dspc->id }} {{ $dspc->ten_kpi }}</div> -->
                    <div class="text-dark mb-2">
                        <i class="bi bi-person me-1"></i>{{ $dspc->ten_nv }}
                    </div>
                    
                </td>
                 <!-- TIẾN ĐỘ(Kèm Dự kiến sau khi duyệt) -->
                <td>
                    <div class="d-flex justify-content-between mb-1 small">
                        <span>Hiện tại: <b>{{$dspc->hieu_suat}}</span>
                    </div>
                    <div class="progress-stacked" style="height: 10px; background-color: #e9ecef; border-radius: 5px; overflow: hidden; display: flex;">
                         <!-- Phần đã đạt được -->
                        <div class="progress-bar bg-success" role="progressbar" style="width: {{ min($dspc->tien_do, 100) }}%"></div>
                         <!-- Phần dự kiến tăng thêm (Dự kiến - Thực tế) -->
                        @if($dspc->tien_do_du_kien > $dspc->tien_do)
                        <div class="progress-bar bg-success bg-opacity-25 progress-bar-striped progress-bar-animated" 
                             role="progressbar" 
                             style="width: {{ min($dspc->tien_do_du_kien - $dspc->tien_do, 100 - $dspc->tien_do) }}%"></div>
                        @endif
                    </div>
                    
                    <div class="mt-1 d-flex justify-content-between" style="font-size: 0.75rem;">
                        <span class="text-success fw-bold">Đạt: {{ round($dspc->tien_do, 1) }}%</span>
                        @if($dspc->tien_do_du_kien > $dspc->tien_do)
                            <span class="text-primary italic">Sau duyệt: {{ $dspc->tien_do_du_kien }}%</span>
                        @endif
                    </div>
                </td>

                <!-- % Thời gian -->
                <td class="text-center">
                    <div class="progress bg-light border" style="height: 18px; border-radius: 4px;">
                        <div class="progress-bar {{ $dspc->tien_do_ngay > 100 ? 'bg-danger' : 'bg-warning text-dark' }} fw-bold shadow-none" 
                             role="progressbar" 
                             style="width: {{ min($dspc->tien_do_ngay, 100) }}%">
                            {{ round($dspc->tien_do_ngay, 0) }}%
                        </div>
                    </div>
                </td>

                <!-- Đánh giá -->
                <td class="text-center">
                    <span class="badge {{ $dspc->tien_do < $dspc->tien_do_ngay ? 'text-danger bg-danger-subtle' : 'text-primary bg-primary-subtle' }} border">
                        {{ $dspc->danh_gia }}
                    </span>
                </td>

                 <!-- Deadline -->
                <td>
                    <div class="small fw-bold {{ str_contains($dspc->deadline, 'Quá hạn') ? 'text-danger' : 'text-success' }}">
                        {{ $dspc->deadline }}
                    </div>
                    <div class="text-muted small">{{ date('d/m/Y', strtotime($dspc->phanCong->ngay_ket_thuc)) }}</div>
                </td>

                 <!-- Trạng thái -->
                <td class="text-center">
                    @php
                        $trang_thai = [
                            'chua_bat_dau'       => ['label' => 'Chưa bắt đầu', 'class' => 'bg-info'],
                            'da_hoan_thanh'  => ['label' => 'Hoàn thành', 'class' => 'bg-success'],
                            'dang_thuc_hien' => ['label' => 'Đang làm', 'class' => 'bg-info'],
                            'dang_no'        => ['label' => 'Đang nợ', 'class' => 'bg-warning text-dark'],
                            'chua_dat'       => ['label' => 'Chưa đạt', 'class' => 'bg-danger'],
                        ];
                        $st = $trang_thai[$dspc->trang_thai_tinh] ?? ['label' => $dspc->trang_thai_tinh, 'class' => 'bg-secondary'];
                    @endphp
                    <span class="badge {{ $st['class'] }} w-100 py-2">{{ $st['label'] }}</span>
                </td>
                <td>
                    @php
                        $bg_MacDinh = 'success'; 
                        if (str_contains($dspc->canh_bao, 'Chưa đạt chu kỳ') || 
                            str_contains($dspc->canh_bao, 'Chưa đạt đa chỉ tiêu') || 
                            str_contains($dspc->canh_bao, 'Quá hạn')) {
                            
                            $bg_MacDinh = 'danger'; 
                            
                        } elseif ( str_contains($dspc->canh_bao, 'Thiếu chu kỳ') ||
                                str_contains($dspc->canh_bao, 'Sắp hết hạn') || 
                                
                                str_contains($dspc->canh_bao, 'Đạt ngưỡng bù')){
                            $bg_MacDinh = 'warning text-dark'; 
                        } elseif (str_contains($dspc->canh_bao, 'Chưa có dữ liệu') || 
                                str_contains($dspc->canh_bao, 'Chưa bắt đầu')) {
                            $bg_MacDinh = 'secondary'; 
                        }elseif(str_contains($dspc->canh_bao, 'Đang đúng tiến độ'))
                            $bg_MacDinh='info';
                    @endphp
                    <span class="badge bg-{{ $bg_MacDinh }} py-2">
                        <!-- {{ $dspc->canh_bao }} -->
                        <div style="white-space: pre-line;" >
                            {{ $dspc->canh_bao }}
                        </div>
                    </span>
                </td>
                 <!-- Hành động: Giữ nguyên tuyệt đối logic -->
                <td class="text-center">
                    @can('manager')
                        @if($dspc->bao_cao_chua_duyet->isNotEmpty())
                            <button type="button" class="btn btn-xs btn-outline-primary btn-xem-bao-cao mb-2" 
                                    data-cv-id="{{ $dspc->bao_cao_chua_duyet->first()->id }}">
                                Phê duyệt
                            </button>
                            <!-- <form action="{{ route('manager.qlcongviec.qltiendo.xembc', $dspc->bao_cao_chua_duyet->first()->id) }}">
                                <button type="submit" class="btn btn-xs btn-outline-primary btn-xem-bao-cao mb-2" 
                                    data-cv-id="{{ $dspc->bao_cao_chua_duyet->first()->id }}">
                                Phê duyệt
                            </button>
                            </form> -->
                        @endif
                            <div class="modal fade" id="modalXemBaoCao" tabindex="-1" aria-hidden="true">
                    <div class="modal-dialog modal-lg modal-dialog-centered">
                        <div class="modal-content">
                            <div class="modal-header bg-dark text-white">
                                <h5 class="modal-title"><i class="bi bi-file-earmark-arrow-up me-2"></i>Chi Tiết Báo Cáo</h5>
                                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                            </div>
                            <div class="modal-body body-xem-bao-cao">
                                <div class="text-center p-3">
                                    <div class="spinner-border text-primary"></div>
                                    <p>Đang tải dữ liệu...</p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                    @endcan
                    <form action="{{route('qlcongviec.xemlsbaocao',  $dspc->id )}}" method="GET">
                        <button type="submit" class="btn btn-sm btn-info" >Xem lịch sử</button>
                    </form>
                </td>
            </tr>
            @endforeach 
            @endforeach
        </tbody>
    </table>