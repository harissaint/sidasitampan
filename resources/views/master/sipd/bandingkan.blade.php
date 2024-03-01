@extends('layouts.app')

@section('title', 'SIPD')

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
                    <li class="breadcrumb-item active" aria-current="page">Bandingkan</li>
                </ol>
            </nav>
        </div>
    </div>
@endsection

@section('content')
    <div id="app">   
        <div class="card">
            <div class="card-body">
                <v-data-table-bandingkan group="{{Auth::user()->group->nama}}" skpd_nama="{{Auth::user()->skpd?->nama}}"></v-data-table-bandingkan>
            </div>
        </div>
    </div>
@endsection
