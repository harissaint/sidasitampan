@extends('layouts.app')

@section('title', 'Prioritas Report Detail')

@section('breadcrumb')
    <div class="page-breadcrumb d-none d-sm-flex align-items-center mb-3">
        <div class="breadcrumb-title pe-3">@yield('title')</div>
        <div class="ps-3">
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb mb-0 p-0">
                    <li class="breadcrumb-item"><a href="{{ route('home') }}"><i class="bx bx-home-alt"></i></a>
                    </li>
                    <li class="breadcrumb-item">Master</li>
                    <li class="breadcrumb-item"><a href="{{ route('master.prioritas-sd.index') }}">Prioritas - Sumber
                            Dana</a></li>
                    <li class="breadcrumb-item">{{ $prioritas->nama }}</li>
                    <li class="breadcrumb-item active" aria-current="page">Report Detail</li>
                </ol>
            </nav>
        </div>
    </div>
@endsection

@section('content')
    <div id="app">
        <div class="card">
            <div class="card-body">
                <div class="mb-3">
                    <h6>Report Detail Data Master Prioritas <strong>{{ $prioritas->nama }}</strong></h6>
                    <h6>Sumber Dana : <strong>{{ $prioritas->tahapan->nama }}</strong></h6>
                </div>

                <v-data-table-report-prioritas-sd group="{{ Auth::user()->group->nama }}"
                    skpd_nama="{{ Auth::user()->skpd?->nama }}" prioritas_id="{{ $prioritas->id }}">
                </v-data-table-report-prioritas-sd>
            </div>
        </div>
    </div>
@endsection
