@extends('layouts.app')

@section('title', 'Dashboard')

@section('content')
    {{-- <div class="row row-cols-1 row-cols-md-2 row-cols-xl-4">
        <div class="col">
            <div class="border-0 card radius-10 border-start border-3 border-info">
                <div class="card-body">
                    <div class="d-flex align-items-center">
                        <div>
                            <p class="mb-0 text-secondary">Total Orders</p>
                            <h4 class="my-1 text-info">4805</h4>
                            <p class="mb-0 font-13">+2.5% from last week</p>
                        </div>
                        <div class="text-white widgets-icons-2 rounded-circle bg-gradient-scooter ms-auto">
                            <i class='bx bxs-cart'></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col">
            <div class="border-0 card radius-10 border-start border-3 border-danger">
                <div class="card-body">
                    <div class="d-flex align-items-center">
                        <div>
                            <p class="mb-0 text-secondary">Total Revenue</p>
                            <h4 class="my-1 text-danger">$84,245</h4>
                            <p class="mb-0 font-13">+5.4% from last week</p>
                        </div>
                        <div class="text-white widgets-icons-2 rounded-circle bg-gradient-bloody ms-auto">
                            <i class='bx bxs-wallet'></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col">
            <div class="border-0 card radius-10 border-start border-3 border-success">
                <div class="card-body">
                    <div class="d-flex align-items-center">
                        <div>
                            <p class="mb-0 text-secondary">Bounce Rate</p>
                            <h4 class="my-1 text-success">34.6%</h4>
                            <p class="mb-0 font-13">-4.5% from last week</p>
                        </div>
                        <div class="text-white widgets-icons-2 rounded-circle bg-gradient-ohhappiness ms-auto">
                            <i class='bx bxs-bar-chart-alt-2'></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col">
            <div class="border-0 card radius-10 border-start border-3 border-warning">
                <div class="card-body">
                    <div class="d-flex align-items-center">
                        <div>
                            <p class="mb-0 text-secondary">Total Customers</p>
                            <h4 class="my-1 text-warning">8.4K</h4>
                            <p class="mb-0 font-13">+8.4% from last week</p>
                        </div>
                        <div class="text-white widgets-icons-2 rounded-circle bg-gradient-blooker ms-auto">
                            <i class='bx bxs-group'></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div><!--end row--> --}}

    <div class="row">
        <div class="col-12">
            <div class="card radius-10">
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-6">
                            <div class="">
                                <nav aria-label="breadcrumb">
                                    <ol class="breadcrumb">
                                        <li class="breadcrumb-item"><a href="{{ route('home') }}?level=1">Anggaran Rekening</a></li>
                                        @isset($breadcumb)
                                            @foreach ($breadcumb as $item)
                                                <li class="breadcrumb-item" aria-current="page">
                                                    <a href="{{ route('home') }}?level={{ $item['level']+1 }}&code={{ $item['kode'] }}">{{ $item['nama'] }}</a>
                                                </li>
                                            @endforeach
                                        @endisset
                                    </ol>
                                </nav>
                                <div id="chart1" data-url="{{ route('home') }}"></div>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="table-responsive">
                                <table class="table">
                                    <thead class="table-light">
                                        <tr>
                                            <th>Kode Rekening</th>
                                            <th>Nama</th>
                                            <th>Total (Rp)</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach ($anggaran as $item)
                                            <tr>
                                                <td>{{ $item['code'] }}</td>
                                                <td>{{ $item['name'] }}</td>
                                                <td class="text-end">{{number_format($item['total'], 0, ',', '.') ?? 0}}</td>
                                            </tr>
                                        @endforeach
                                        <tr>
                                            <td>Total</td>
                                            <td colspan="3" class="text-end">{{number_format($total, 0, ',', '.')}}</td>
                                        </tr>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div><!--end row-->
    
    <div class="row">
        <div class="col-12">
            <div class="card radius-10">
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-6">
                            <div class="">
                                <nav aria-label="breadcrumb">
                                    <ol class="breadcrumb">
                                        @if ($level == 1)
                                            <li class="breadcrumb-item"><a href="{{ route('home') }}?levelUrusan=1">Anggaran Urusan</a></li>
                                        @else
                                            <li class="breadcrumb-item"><a href="{{ route('home') }}?level={{ $level }}&code={{ $code }}&levelUrusan=1">Anggaran Urusan</a></li>
                                        @endif
                                        @isset($breadcumbUrusan)
                                            @foreach ($breadcumbUrusan as $item)
                                                <li class="breadcrumb-item" aria-current="page">
                                                    <a href="{{ route('home') }}?levelUrusan={{ $item['level']+1 }}&codeUrusan={{ $item['code'] }}">{{ $item['name'] }}</a>
                                                </li>
                                            @endforeach
                                        @endisset
                                    </ol>
                                </nav>
                                <div id="chart2" data-url="{{ route('home') }}"></div>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="table-responsive">
                                <table class="table">
                                    <thead class="table-light">
                                        <tr>
                                            <th>Kode Rekening</th>
                                            <th>Nama</th>
                                            <th>Total (Rp)</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach ($urusan as $item)
                                            <tr>
                                                <td>{{ $item['code'] }}</td>
                                                <td>{{ $item['name'] }}</td>
                                                <td class="text-end">{{number_format($item['total'], 0, ',', '.') ?? 0}}</td>
                                            </tr>
                                        @endforeach
                                        <tr>
                                            <td>Total</td>
                                            <td colspan="3" class="text-end">{{number_format($total, 0, ',', '.')}}</td>
                                        </tr>
                                    </tbody>
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
    <script src="{{ asset('app/assets/js/index.js') }}"></script>

    <script>
        var anggaranData = [];
        @foreach ($anggaran as $item)
            anggaranData.push({
                name: "{{ $item['name'] }}",
                level: "{{ $item['level'] }}",
                toLevel: "{{ $item['toLevel'] }}",
                code: "{{ $item['code'] }}",
                y: {{ $item['total'] ?? 0 }},
            });
        @endforeach

        var urusanData = [];
        @foreach ($urusan as $item)
            urusanData.push({
                name: "{{ $item['name'] }}",
                level: "{{ $item['level'] }}",
                toLevel: "{{ $item['toLevel'] }}",
                code: "{{ $item['code'] }}",
                y: {{ $item['total'] ?? 0 }},
            });
        @endforeach
        </script>
@endsection
