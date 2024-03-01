@extends('layouts.app')

@section('title', 'Dashboard Perbandingan by Rekening')

@section('style')
    <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
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
                        <div class="col-md-12">
                            <label for="skpd">SKPD <span class="text-danger">*</span></label>
                            @can('isSkpd', App\Model\User::class)
                                <h5>{{ Auth::user()->skpd->nama }}</h5>
                            @else
                                <select name="skpd" class="form-select select2-yes" aria-label="Default select example">
                                    <option value="" selected>Semua SKPD</option>
                                    @foreach ($skpds as $skpd)
                                        <option value="{{ $skpd->kode }}"
                                            {{ request()->skpd == $skpd->kode ? 'selected' : '' }}>
                                            {{ $skpd->nama }}
                                        </option>
                                    @endforeach
                                </select>
                            @endcan
                        </div>
                        <div class="col-md-6">
                            <label for="tahap">Tahap 1 <span class="text-danger">*</span></label>
                            <select name="tahap_1" class="form-select select2-yes" aria-label="Default select example" required>
                                @foreach ($tahaps as $tahap)
                                    <option value="{{ $tahap->id }}"
                                        {{ request()->tahap_1 == $tahap->id ? 'selected' : '' }}>
                                        {{ $tahap->nama }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-md-6">
                            <label for="tahap">Tahap 2 <span class="text-danger">*</span></label>
                            <select name="tahap_2" class="form-select select2-yes" aria-label="Default select example" required>
                                @foreach ($tahaps as $tahap)
                                    <option value="{{ $tahap->id }}"
                                        {{ request()->tahap_2 == $tahap->id ? 'selected' : '' }}>
                                        {{ $tahap->nama }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                        <input type="hidden" name="level" value="{{ request()->level }}">
                        <input type="hidden" name="code" value="{{ request()->code }}">

                        <button type="submit" class="btn btn-primary">
                            <i class="bx bx-search"></i>Tampilkan
                        </button>
                    </form>
                    <div class="row">
                        <div class="col-md-12">
                            <div class="">
                                <nav aria-label="breadcrumb">
                                    <ol class="breadcrumb">
                                        <li class="breadcrumb-item"><a
                                                href="{{ route('perbandingan.rekening') }}?tahap_1={{request()->tahap_1}}&tahap_2={{request()->tahap_2}}&skpd={{ request()->skpd }}&level=1">Anggaran
                                                Rekening</a></li>
                                        @isset($breadcumb)
                                            @foreach ($breadcumb as $item)
                                                <li class="breadcrumb-item" aria-current="page">
                                                    <a
                                                        href="{{ route('perbandingan.rekening') }}?tahap_1={{request()->tahap_1}}&tahap_2={{request()->tahap_2}}&skpd={{ request()->skpd }}&level={{ $item['level'] + 1 }}&code={{ $item['kode'] }}">{{ $item['nama'] }}</a>
                                                </li>
                                            @endforeach
                                        @endisset
                                    </ol>
                                </nav>
                                <div id="chart3" data-url="{{ route('perbandingan.rekening') }}"></div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div><!--end row-->
@endsection

@section('script')
    <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
    <script src="{{ asset('app/assets/js/highcharts.js') }}"></script>
    <script src="{{ asset('app/assets/js/highcharts_modules_data.js') }}"></script>
    <script src="{{ asset('app/assets/js/highcharts_modules_drilldown.js') }}"></script>
    <script src="{{ asset('app/assets/js/highcharts_modules_exporting.js') }}"></script>
    <script src="{{ asset('app/assets/js/highcharts_modules_export-data.js') }}"></script>
    <script src="{{ asset('app/assets/js/highcharts_modules_accessibility.js') }}"></script>
    <script src="{{ asset('app/assets/js/index_perbandingan_rekening.js') }}"></script>

    <script>
        $('.select2-yes').select2();

        var category = [];
        @foreach ($anggaran as $item)
        category.push("{{ $item['name'] }}");
        @endforeach

        var perbandinganData = [];
        @foreach ($anggaran as $item)
            perbandinganData.push({
                name: "{{ $item['name'] }}",
                level: "{{ $item['level'] }}",
                toLevel: "{{ $item['toLevel'] }}",
                code: "{{ $item['code'] }}",
                skpd: "{{ request()->skpd }}",
                tahap_1: "{{ request()->tahap_1 }}",
                tahap_2: "{{ request()->tahap_2 }}",
                y: {{ $item['total_1'] ?? 0 }},
            });
        @endforeach

        var perbandinganData2 = [];
        @foreach ($anggaran as $item)
            perbandinganData2.push({
                name: "{{ $item['name'] }}",
                level: "{{ $item['level'] }}",
                toLevel: "{{ $item['toLevel'] }}",
                code: "{{ $item['code'] }}",
                skpd: "{{ request()->skpd }}",
                tahap_1: "{{ request()->tahap_1 }}",
                tahap_2: "{{ request()->tahap_2 }}",
                y: {{ $item['total_2'] ?? 0 }},
            });
        @endforeach
    </script>
@endsection
