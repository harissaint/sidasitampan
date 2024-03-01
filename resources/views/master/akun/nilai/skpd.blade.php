@extends('layouts.app')

@section('title', 'Akun Rekening')

@section('breadcrumb')
    <div class="page-breadcrumb d-none d-sm-flex align-items-center mb-3">
        <div class="breadcrumb-title pe-3">@yield('title')</div>
        <div class="ps-3">
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb mb-0 p-0">
                    <li class="breadcrumb-item"><a href="{{ route('home') }}"><i class="bx bx-home-alt"></i></a>
                    </li>
                    <li class="breadcrumb-item">Master</li>
                    <li class="breadcrumb-item"><a href="{{ route('master.akun.nilai') }}">Akun Rekening</a></li>
                    <li class="breadcrumb-item">{{ $akun->nama ?? $akun->nama_akun }}</li>
                    <li class="breadcrumb-item active" aria-current="page">Nilai</li>
                </ol>
            </nav>
        </div>
    </div>
@endsection

@section('content')
    <div id="app">
        <div class="card">
            <div class="card-body">
                <v-data-table-akun-skpd akun_kode="{{ $akun->kode ?? $akun->kode_akun }}"
                    akun_nama="{{ $akun->nama ?? $akun->nama_akun }}"
                    tahapan_id="{{ request()->get('tahapan_id') }}"></v-data-table-akun-skpd>
            </div>
        </div>
    </div>
@endsection
