@extends('layouts.app')

@section('title', 'Tahapan')

@section('style')
    <link rel="stylesheet" href="{{ asset('app/assets/plugins/jstree/dist/themes/default/style.min.css') }}" />
@endsection

@section('breadcrumb')
    <div class="page-breadcrumb d-none d-sm-flex align-items-center mb-3">
        <div class="breadcrumb-title pe-3">@yield('title')</div>
        <div class="ps-3">
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb mb-0 p-0">
                    <li class="breadcrumb-item"><a href="{{ route('home') }}"><i class="bx bx-home-alt"></i></a>
                    </li>
                    <li class="breadcrumb-item">Master</li>
                    <li class="breadcrumb-item"><a href="{{ route('master.prioritas-sd-sk.index') }}">Prioritas - Sumber
                            Dana (Sub Kegiatan)</a></li>
                    <li class="breadcrumb-item">{{ $prioritas->nama }}</li>
                    <li class="breadcrumb-item active">Detail</li>
                </ol>
            </nav>
        </div>
    </div>
@endsection

@section('content')
    <div id="app">
        <div class="card mb-5">
            <div class="card-body">
                <div class="d-flex justify-content-between align-items-center mb-3">
                    <div>
                        <h6>List Data Master Prioritas <strong>{{ $prioritas->nama }}</strong></h6>
                        <h6>Sumber Dana : <strong>{{ $prioritas->tahapan->nama }}</strong></h6>
                    </div>

                    <button type="button" class="btn btn-primary btn-sm btn-save-prioritas">
                        <i class="bx bx-save"></i> Simpan
                    </button>
                </div>

                <div id="using_json">
                </div>
            </div>
        </div>
    </div>
@endsection

@section('script')
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script src="{{ asset('app/assets/plugins/jstree/dist/jstree.min.js') }}"></script>
    <script>
        $(function() {
            $('#using_json').jstree({
                'core': {
                    'data': {!! $jtree !!}
                },
                "checkbox": {
                    "keep_selected_style": false
                },
                "plugins": ["wholerow", "checkbox"]
            });

            // on click btn-save-prioritas
            $('.btn-save-prioritas').on('click', function() {
                var selected = $('#using_json').jstree("get_selected", true);
                var data = [];
                $.each(selected, function(i, node) {
                    data.push(node.id);
                    // console.log(node.id);
                });

                $.ajax({
                    url: "{{ route('master.prioritas-sd-sk.upsertmap', $prioritas->id) }}",
                    type: 'POST',
                    data: {
                        _token: '{{ csrf_token() }}',
                        data: {
                            ids: data
                        }
                    },
                    success: function(response) {
                        console.log(response);
                        if (response.status == 200) {
                            Swal.fire({
                                icon: 'success',
                                title: 'Berhasil',
                                text: response.message,
                                showConfirmButton: false,
                                timer: 1500
                            });
                        } else {
                            Swal.fire({
                                icon: 'error',
                                title: 'Gagal',
                                text: response.message,
                                showConfirmButton: false,
                                timer: 1500
                            });
                        }
                    },
                    error: function(response) {
                        Swal.fire({
                            icon: 'error',
                            title: 'Gagal',
                            text: response.message,
                            showConfirmButton: false,
                            timer: 1500
                        });
                    }
                });
            });
        });
    </script>
@endsection
