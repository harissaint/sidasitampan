@extends('layouts.app')

@section('title', 'Alokasi Anggaran Wajib')

@section('style')
    <style>
        .alokasi:hover {
            background-color: #f1f1f1;
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
                    <form action="" method="GET" class="mb-3">
                        <div class="input-group">
                            <span class="input-group-text" id="inputGroup-sizing-sm">
                                <i class="bx bx-filter-alt"></i>
                            </span>
                            <select name="tahapan" class="form-select" aria-label="Default select example"
                                onchange="this.form.submit();">
                                {{-- <option value="" selected>Pilih Tahap</option> --}}
                                @foreach ($tahaps as $tahap)
                                    <option value="{{ $tahap->id }}"
                                        {{ request()->tahapan == $tahap->id ? 'selected' : '' }}>
                                        {{ $tahap->nama }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                    </form>
                    <div class="row g-3">
                        @foreach ($results as $alokasi)
                            <div class="col-lg-4">
                                <div class="containter border alokasi">
                                    <div class="p-2 border">
                                        <h5 class="mb-0">Anggaran {{ $alokasi->uraian }}</h5>
                                    </div>
                                    <div class="p-3">
                                        <div class="progress mb-2" style="height: 30px;">
                                            <div class="progress-bar progress-bar-striped progress-bar-animated"
                                                role="progressbar" style="width: {{ $alokasi->persen }}%;"
                                                aria-valuenow="{{ $alokasi->persen }}" aria-valuemin="0"
                                                aria-valuemax="100"></div>
                                        </div>
                                        <div class="mb-2">
                                            <h6 class="mb-0">Rp {{ number_format($alokasi->total, 0, '', '.') }}
                                                ({{ $alokasi->persen }}%)</h6>
                                            <p class="mb-0">Minimal Alokasi Rp
                                                {{ number_format($alokasi->min_alokasi, 0, '', '.') }}
                                                ({{ $alokasi->min_persen }}%)</p>
                                            {{-- <p>Total Alokasi Rp {{number_format($pendidikan->total, 0, '', '.')}}</p> --}}
                                        </div>
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>
            </div>
        </div>
    </div><!--end row-->
@endsection

@section('script')
@endsection
