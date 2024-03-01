<!doctype html>
<html lang="en">

<head>
    <!-- Required meta tags -->
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <!-- CSRF Token -->
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <!--favicon-->
    <link rel="icon" href="{{ asset('app/assets/images/favicon-32x32.png') }}" type="image/png" />
    <!--plugins-->
    <link href="{{ asset('app/assets/plugins/simplebar/css/simplebar.css') }}" rel="stylesheet" />
    <link href="{{ asset('app/assets/plugins/perfect-scrollbar/css/perfect-scrollbar.css') }}" rel="stylesheet" />
    <link href="{{ asset('app/assets/plugins/metismenu/css/metisMenu.min.css') }}" rel="stylesheet" />
    <!-- loader-->
    <link href="{{ asset('app/assets/css/pace.min.css') }}" rel="stylesheet" />
    <script src="{{ asset('app/assets/js/pace.min.js') }}"></script>
    <!-- Bootstrap CSS -->
    @vite(['resources/sass/app.scss'])
    <link href="{{ asset('app/assets/css/bootstrap.min.css') }}" rel="stylesheet">
    <link href="{{ asset('app/assets/css/bootstrap-extended.css') }}" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Roboto:wght@400;500&display=swap" rel="stylesheet">
    <link href="{{ asset('app/assets/css/app.css') }}" rel="stylesheet">
    <link href="{{ asset('app/assets/css/icons.css') }}" rel="stylesheet">
    <title>Log In - Sidasi Tampan Kota Cimahi</title>
    <!-- Include script -->
    <script type="text/javascript">
        function callbackThen(response) {
            // read Promise object
            response.json().then(function(data) {
                if (data.success && data.score >= 0.6) {
                    console.log('recaptcha success');
                } else {
                    document.getElementById('loginForm').addEventListener('submit', function(event) {
                        event.preventDefault();
                        alert('recaptcha error');
                    });
                }
            });
        }

        function callbackCatch(error) {
            console.error(error);
            alert('recaptcha error');
        }
    </script>

    {!! htmlScriptTagJsApi([
        'callback_then' => 'callbackThen',
        'callback_catch' => 'callbackCatch',
    ]) !!}

</head>

<body class="bg-login">
    <!--wrapper-->
    <div class="wrapper">
        <div class="section-authentication-signin d-flex align-items-center justify-content-center my-5 my-lg-0">
            <div class="container-fluid">
                <div class="row row-cols-1 row-cols-lg-2 row-cols-xl-3">
                    <div class="col mx-auto">
                        <div class="card">
                            <div class="card-body">
                                <div class="mb-4 text-center">
                                    <img src="{{ asset('app/assets/images/icon.png') }}" width="180"
                                        alt="" />
                                </div>
                                <div class="border p-4 rounded">
                                    <div class="text-center">
                                        <h3 class="">Selamat Datang!</h3>
                                    </div>
                                    <div class="d-grid">
                                        <a class="btn mt-4 shadow-sm btn-white" href="{{ route('auth.google') }}">
                                            <span class="d-flex justify-content-center align-items-center">
                                                <img class="me-2"
                                                    src="{{ asset('app/assets/images/icons/search.svg') }}"
                                                    width="16" alt="Image Description">
                                                <span>Log In dengan Google</span>
                                            </span>
                                        </a>
                                    </div>
                                    <div class="login-separater text-center"> <span>Atau Log In dengan EMAIL</span>
                                        <hr />
                                    </div>
                                    @if (session('success'))
                                        <div class="alert alert-success alert-dismissible fade show" role="alert">
                                            <strong>Sukses!</strong> {{ session('success') }}
                                            <button type="button" class="btn-close" data-bs-dismiss="alert"
                                                aria-label="Close"></button>
                                        </div>
                                    @endif
                                    <div class="form-body">
                                        <form id="loginForm" method="POST" action="{{ route('login') }}" class="row g-3">
                                            @csrf
                                            <div class="col-12">
                                                <label for="inputEmailAddress" class="form-label">Email Address</label>
                                                <input name="email" type="email"
                                                    class="form-control @error('email') is-invalid @enderror"
                                                    id="inputEmailAddress" placeholder="Email Address" required>

                                                @error('email')
                                                    <span class="invalid-feedback" role="alert">
                                                        <strong>{{ $message }}</strong>
                                                    </span>
                                                @enderror
                                            </div>
                                            <div class="col-12">
                                                <label for="inputChoosePassword" class="form-label">Enter
                                                    Password</label>
                                                <div class="input-group" id="show_hide_password">
                                                    <input name="password" type="password"
                                                        class="form-control border-end-0  @error('password') is-invalid @enderror"
                                                        id="inputChoosePassword" placeholder="Enter Password" required>
                                                    <a href="javascript:;" class="input-group-text bg-transparent"><i
                                                            class='bx bx-hide'></i></a>
                                                </div>

                                                @error('password')
                                                    <span class="invalid-feedback" role="alert">
                                                        <strong>{{ $message }}</strong>
                                                    </span>
                                                @enderror
                                            </div>
                                            <div class="col-md-6">
                                                <div class="form-check form-switch">
                                                    <input class="form-check-input" type="checkbox"
                                                        id="flexSwitchCheckChecked" checked>
                                                    <label class="form-check-label"
                                                        for="flexSwitchCheckChecked">Remember Me</label>
                                                </div>
                                            </div>
                                            {{-- <div class="col-md-6 text-end"> <a
                                                    href="authentication-forgot-password.html">Forgot Password ?</a>
                                            </div> --}}
                                            <div class="col-12">
                                                <div class="d-grid">
                                                    <button type="submit" class="btn btn-primary"><i
                                                            class="bx bxs-lock-open"></i>Log In</button>
                                                </div>
                                            </div>
                                        </form>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <!--end row-->
            </div>
        </div>
    </div>
    <!--end wrapper-->
    <!-- Bootstrap JS -->
    <script src="{{ asset('app/assets/js/bootstrap.bundle.min.js') }}"></script>
    <!--plugins-->
    <script src="{{ asset('app/assets/js/jquery.min.js') }}"></script>
    <script src="{{ asset('app/assets/plugins/simplebar/js/simplebar.min.js') }}"></script>
    <script src="{{ asset('app/assets/plugins/metismenu/js/metisMenu.min.js') }}"></script>
    <script src="{{ asset('app/assets/plugins/perfect-scrollbar/js/perfect-scrollbar.js') }}"></script>
    <!--Password show & hide js -->
    <script>
        $(document).ready(function() {
            $("#show_hide_password a").on('click', function(event) {
                event.preventDefault();
                if ($('#show_hide_password input').attr("type") == "text") {
                    $('#show_hide_password input').attr('type', 'password');
                    $('#show_hide_password i').addClass("bx-hide");
                    $('#show_hide_password i').removeClass("bx-show");
                } else if ($('#show_hide_password input').attr("type") == "password") {
                    $('#show_hide_password input').attr('type', 'text');
                    $('#show_hide_password i').removeClass("bx-hide");
                    $('#show_hide_password i').addClass("bx-show");
                }
            });
        });
    </script>
    <!--app JS-->
    {{-- <script src="{{ asset('app/assets/js/app.js') }}"></script> --}}
</body>

</html>
