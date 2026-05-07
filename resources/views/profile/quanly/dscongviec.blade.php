<div class="card border-0 shadow-sm mb-4 mt-3 rounded-3">
    <div class="card-body p-4">
        <h6 class="fw-bold text-uppercase small text-primary mb-3">Danh sách KPI đã giao</h4>
            @foreach($group as $kpiId => $data)
            <div class="accordion-item mb-3 border shadow-sm rounded">
                <h2 class="accordion-header">
                    <button class="accordion-button collapsed bg-white rounded py-3" type="button" data-bs-toggle="collapse" data-bs-target="#collapse-{{ $kpiId }}">
                        <div class="row w-100 align-items-center">
                            <div class="col-md-5">
                                <span class="fw-bold text-muted">{{ $data['ten_kpi'] }}</span>
                            </div>
                            <div class="col-md-4">
                                <div class="d-flex align-items-center">
                                    <div class="progress flex-grow-1 me-2" style="height: 10px;">
                                        <div class="progress-bar bg-success" style="width: {{ $data['tien_do_trung_binh'] }}%"></div>
                                    </div>
                                    <small class="fw-bold">{{ $data['tien_do_trung_binh'] }}%</small>
                                </div>
                            </div>
                            <div class="col-md-3 text-end">
                                <span class="badge rounded-pill bg-info">{{ $data['so_nhan_vien'] }} Nhân viên</span>
                            </div>
                        </div>
                    </button>
                </h2>
                
                <div id="collapse-{{ $kpiId }}" class="accordion-collapse collapse" data-bs-parent="#project">
                    <div class="accordion-body bg-light">
                        <div class="table-responsive">
                            <table class="table table-bordered bg-white mb-0">
                                <thead class="table-secondary">
                                    <tr>
                                        <th>Nhân viên</th>
                                        <th class="text-center">Bắt đầu</th>
                                        <th class="text-center">Tiến độ</th>
                                        <th class="text-center">Trạng thái</th>
                                        <th class="text-center">Thao tác</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($data['chi_tiet'] as $pc)
                                    <tr>
                                        <td><i class="bi bi-person-circle me-1"></i> {{ $pc->nguoiDuocGiao->name }}</td>
                                        <td class="text-center">{{ date('d/m/Y', strtotime($pc->ngay_bat_dau)) }}</td>
                                        <td class="text-center">
                                            <span class="fw-bold">{{ $pc->tien_do }}%</span>
                                        </td>
                                        <td class="text-center">
                                             @php
                                                $trang_thai = [
                                                    'da_hoan_thanh'  => ['label' => 'Hoàn thành', 'class' => 'bg-success'],
                                                    'dang_thuc_hien' => ['label' => 'Đang làm', 'class' => 'bg-info'],
                                                    'dang_no'        => ['label' => 'Đang nợ', 'class' => 'bg-warning text-dark'],
                                                    'chua_dat'       => ['label' => 'Chưa đạt', 'class' => 'bg-danger'],
                                                ];
                                                $st = $trang_thai[$pc->trang_thai_tinh] ?? ['label' => $pc->trang_thai_tinh, 'class' => 'bg-secondary'];
                                            @endphp
                                            <span class="badge {{ $st['class'] }} w-100 py-2">{{ $st['label'] }}</span>
                                        </td>
                                        <td class="text-center">
                                            <form action="{{route('qlcongviec.xemlsbaocao',  $pc->id )}}" method="GET">
                                                <button type="submit" class="btn btn-sm btn-outline-primary" >Xem lịch sử {{$pc->id}}</button>
                                            </form>
                                        </td>
                                    </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
            @endforeach
        
    </div>
</div>