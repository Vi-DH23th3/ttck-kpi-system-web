
<form action="{{ route('manager.qlcongviec.giaokpi.import')}}" method="POST" enctype="multipart/form-data" id="form-import-kpi">
    @csrf
    <div class="row">
        <div class="col-md-6 mb-3">
            <label for="import_file_kpi" class="form-label">Chọn file KPI để import</label>
            <input class="form-control" type="file" id="import_file_kpi" name="import_file_kpi" accept=".xlsx, .xls">
        </div>
        <div class="col-md-6 d-flex align-items-end mb-3">
            <button type="submit" class="btn btn-primary">Import KPI</button>
        </div>
    </div>
</form>   

@if($duLieuImport)
<div class="row">
    <div class="col-md-12 mt-4">
        <h5>Kết quả import:</h5>
            <div class="table-responsive border rounded" style="height: auto; overflow-y: auto;" id="giaochitieu-table-user">
                <table class="table table-sm table-hover mb-0" >
                    <thead class="table-light sticky-top">
                        <tr>
                            <th>STT</th>
                            <th>Danh mục công việc</th>
                            <th class="text-center">Tên KPI</th>
                            <th>Chỉ tiêu</th>
                            <th>Đơn vị</th>
                            <th>Chu kỳ</th>
                            <th>Ghi chú</th>
                            <th>Hành động</th>
                        </tr>
                    </thead>
                    <tbody>  
                        @foreach($duLieuImport as $index => $item)
                        <tr class="kpi-row {{$item['danh_dau'] ? 'table-warning' : ''}}" data-index="{{ $index }}">
                            <td>{{$index+1}}</td>
                            <td class="fw-bold text-primary">{{ $item['danh_muc'] }}</td>
                            <td>{{ $item['ten_kpi'] }}</td>
                            <td>{{ $item['chi_tieu'] }}</td>
                            <td>{{ $item['don_vi'] }}</td>
                            <td>{{ $item['chu_ky'] }}</td>
                            <td>{{ $item['ghi_chu'] }}
                            </td>
                            <td>
                                <button type="button" class="btn btn-outline-primary btn-giao"
                                    data-index="{{ $index }}">
                                    <i class="bi bi-send-plus-fill me-1"></i> Giao chỉ tiêu
                                </button>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>                                 
     </div>
</div>
@endif