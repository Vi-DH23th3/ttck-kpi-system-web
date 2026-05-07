@extends('layouts.app')
@section('title', 'Bổ sung thông tin')

@section('content')
<div class="container mt-5 bg-white p-4 shadow border rounded w-50 mx-auto">
    <div class="mb-3 text-center text-primary fs-3">Bổ sung thông tin</div>
        <div class="content w-75 mx-auto">
            <form method="POST" action="{{route('bosungtt.post', Auth::id())}}">
            @csrf
            <div class="mb-3">
                <div class="input-group">
                    <select name="chuc_vu" class="form-select border-2 edit-chucvu">
                        <option class="menu_chucvu" value="">Chọn chức vụ</option>
                        @foreach($dschucvu as $cv)
                        <option value="{{$cv->id}}" class="menu_chucvu">{{$cv->ten_chuc_vu}}</option>
                        @endforeach
                    </select>
                    <select name="don_vi_id" class="form-select border-2 edit-donvi">
                            <option class="" value="0">Chọn đơn vị</option>
                        @foreach($dsdonvi as $dsdv)
                        <option value="{{$dsdv->id}}" >{{$dsdv->ten_don_vi}}</option>
                        @endforeach
                    </select>
                </div>
            </div>
            <div class="d-grid gap-2">
                <button type="button" class="btn btn-info text-white btn-lg fw-bold edit-submit shadow-sm" onclick="xacNhan(this)" data-message="Xác nhận cập nhật thông tin?">Cập nhật thông tin</button>
            </div>
            </form>
        </div>
    </div>
</div>
@endsection