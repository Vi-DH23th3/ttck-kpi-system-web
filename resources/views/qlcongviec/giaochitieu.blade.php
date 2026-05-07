@extends('layouts.admin')
@section('title', 'Giao KPI')

@section('content')
<style>
    .nav-tabs .nav-link:link{
        color: dark;
        background-color: white;
    }
    .nav-tabs .nav-link:visited{
        color: yellow;
        background-color: green;
    }
    .nav-tabs .nav-link:hover{
        color: blue;
        background-color: yellow;
    }
    .nav-tabs .nav-link:active{
        color: red;
        background-color: orange;
    }
    
</style>
@php
    $activeTab = session('tab', 'thucong');
@endphp
<div class="container-fluid mt-3">
    <div class="col-md-12">
        <ul class="nav nav-tabs">
            <li class="nav-item">
                <button class="nav-link {{ $activeTab == 'thucong' ? 'active' : '' }}" data-bs-toggle="tab" data-bs-target="#thucong">Giao KPI</button>
            </li>

            <li class="nav-item">
                <button class="nav-link {{ $activeTab == 'importfile' ? 'active' : '' }}" data-bs-toggle="tab" data-bs-target="#importfile">
                    Import file KPI
                </button>
            </li>
        </ul>
    </div>
    
    <div class="tab-content">
        <div class="tab-pane fade {{ $activeTab == 'thucong' ? 'show active' : '' }}" id="thucong">
            @include('qlcongviec.giaokpi.giaokpi')
        </div>

        <div class="tab-pane fade {{ $activeTab == 'importfile' ? 'show active' : '' }}" id="importfile">
            @include('qlcongviec.giaokpi.giaokpifile')
        </div>
    </div>

</div>
    
@push('script')
<script>
    const URL_GIAO_CHI_TIEU = "{{ route('manager.qlcongviec.giaochitieu') }}";
</script>
  <script src="{{ asset('js/giaochitieu.js') }}"></script>
    <script src="{{ asset('js/thongbaoxacnhan.js') }}"></script>
@endpush
@endsection
