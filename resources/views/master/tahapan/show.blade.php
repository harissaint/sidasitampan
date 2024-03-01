@extends('layouts.app')

@section('title', 'Tahapan')

@section('breadcrumb')
    <div class="page-breadcrumb d-none d-sm-flex align-items-center mb-3">
        <div class="breadcrumb-title pe-3">@yield('title')</div>
        <div class="ps-3">
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb mb-0 p-0">
                    <li class="breadcrumb-item"><a href="{{ route('home') }}"><i class="bx bx-home-alt"></i></a>
                    </li>
                    <li class="breadcrumb-item">Master</li>
                    <li class="breadcrumb-item"><a href="{{ route('master.tahapan.index') }}">Penganggaran</a></li>
                    <li class="breadcrumb-item">{{ $tahapan->nama }}</li>
                    <li class="breadcrumb-item active">Detail</li>
                </ol>
            </nav>
        </div>
    </div>
@endsection

@section('content')
    <div id="app">
        <div class="card">
            <div class="card-body">
                <div class="d-flex justify-content-between align-items-center mb-3">
                    <h6>List Data Master SIPD Tahap <strong>{{ $tahapan->nama }}</strong></h6>
                </div>
                <v-data-table-tahapan-detail tahapan_id="{{$tahapan->id}}"></v-data-table-tahapan-detail>
            </div>
        </div>
    </div>
@endsection
