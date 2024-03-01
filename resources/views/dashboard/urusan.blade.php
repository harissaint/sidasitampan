@extends('layouts.app')

@section('title', 'Dashboard by Urusan')

@section('style')
    <style>
        .table th:last-child,
        .table td:last-child {
            position: sticky;
            right: 0;
            background: #fff;
            border-left: 1px solid #dee2e6;
            z-index: 1;
        }
    </style>
@endsection

@section('breadcrumb')
    <div class="page-breadcrumb d-none d-sm-flex align-items-center mb-3">
        <div class="breadcrumb-title pe-3">@yield('title')</div>
    </div>
@endsection

@section('content')
    <div class="row">
        <div class="col-12">
            <div class="card radius-10">
                <div class="card-body">
                    <form action="" method="GET" class="row g-3 mb-3">
                        <div class="col-md-6">
                            <label for="tahap">Tahap</label>
                            <div class="input-group">
                                <span class="input-group-text" id="inputGroup-sizing-sm">
                                    <i class="bx bx-filter-alt"></i>
                                </span>
                                <select name="tahap" class="form-select" aria-label="Default select example"
                                    onchange="this.form.submit();">
                                    @foreach ($tahaps as $tahap)
                                        <option value="{{ $tahap->id }}"
                                            {{ request()->tahap == $tahap->id ? 'selected' : '' }}>
                                            {{ $tahap->nama }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <label for="skpd">SKPD</label>
                            @can('isSkpd', App\Model\User::class)
                                <h5>{{ Auth::user()->skpd->nama }}</h5>
                            @else
                                <div class="input-group mb-3">
                                    <span class="input-group-text" id="inputGroup-sizing-sm">
                                        <i class="bx bx-filter-alt"></i>
                                    </span>
                                    <select name="skpd" class="form-select" aria-label="Default select example"
                                        onchange="this.form.submit();">
                                        <option value="" selected>Semua SKPD</option>
                                        @foreach ($skpds as $skpd)
                                            <option value="{{ $skpd->kode }}"
                                                {{ request()->skpd == $skpd->kode ? 'selected' : '' }}>
                                                {{ $skpd->nama }}
                                            </option>
                                        @endforeach
                                    </select>
                                </div>
                            @endcan
                        </div>
                        <input type="hidden" name="level" value="{{ request()->level }}">
                        <input type="hidden" name="code" value="{{ request()->code }}">
                    </form>
                    <div class="row">
                        <div class="col-md-6">
                            <div class="">
                                <nav aria-label="breadcrumb">
                                    <ol class="breadcrumb">
                                        <li class="breadcrumb-item"><a
                                                href="{{ route('urusan') }}?tahap={{request()->tahap}}&skpd={{ request()->skpd }}&level=1">Anggaran
                                                Urusan</a></li>
                                        @isset($breadcrumb)
                                            @foreach ($breadcrumb as $item)
                                                <li class="breadcrumb-item" aria-current="page">
                                                    <a
                                                        href="{{ route('urusan') }}?tahap={{request()->tahap}}&skpd={{ request()->skpd }}&level={{ $item['level'] + 1 }}&code={{ $item['code'] }}">{{ $item['name'] }}</a>
                                                </li>
                                            @endforeach
                                        @endisset
                                    </ol>
                                </nav>
                                <div id="chart2" data-url="{{ route('urusan') }}"></div>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="table-responsive">
                                <table class="table">
                                    <thead class="table-light">
                                        <tr>
                                            <th>Kode {{$urutan[request()->level ? request()->level-1: 0]}}</th>
                                            <th>Nama {{$urutan[request()->level ? request()->level-1: 0]}}</th>
                                            <th>Total (Rp)</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach ($urusan as $item)
                                            <tr>
                                                <td>{{ $item['code'] }}</td>
                                                <td>{{ $item['name'] }}</td>
                                                <td class="text-end">{{ number_format($item['total'], 0, ',', '.') ?? 0 }}
                                                </td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                    <tfoot>
                                        <tr>
                                            <th colspan="2">Total</th>
                                            <th class="text-end">{{ number_format($total, 0, ',', '.') }}</th>
                                        </tr>
                                    </tfoot>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div><!--end row-->
@endsection

@section('script')
    <script src="{{ asset('app/assets/js/highcharts.js') }}"></script>
    <script src="{{ asset('app/assets/js/highcharts_modules_data.js') }}"></script>
    <script src="{{ asset('app/assets/js/highcharts_modules_drilldown.js') }}"></script>
    <script src="{{ asset('app/assets/js/highcharts_modules_exporting.js') }}"></script>
    <script src="{{ asset('app/assets/js/highcharts_modules_export-data.js') }}"></script>
    <script src="{{ asset('app/assets/js/highcharts_modules_accessibility.js') }}"></script>
    <script src="{{ asset('app/assets/js/index_urusan.js') }}"></script>

    <script>
        var urusanData = [];
        @foreach ($urusan as $item)
            urusanData.push({
                name: "{{ $item['name'] }}",
                level: "{{ $item['level'] }}",
                toLevel: "{{ $item['toLevel'] }}",
                code: "{{ $item['code'] }}",
                skpd: "{{ request()->skpd }}",
                tahap: "{{ request()->tahap }}",
                y: {{ $item['total'] ?? 0 }},
            });
        @endforeach
    </script>
@endsection
