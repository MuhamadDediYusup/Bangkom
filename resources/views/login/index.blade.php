<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="title" content="BANG KOM | Aplikasi Pengembangan Kompetensi ASN Kabupaten Sleman">
    <meta name="description"
        content="Aplikasi Pengembangan Kompetensi ASN di Sleman (Abangkomandan) digunakan untuk meningkatkan pelayanan dan pengelolaan pengembangan kompetensi ASN yang dikelola oleh Badan Kepegawaian, Pendidikan dan Pelatihan (BKPP) Kabupaten Sleman.">
    <meta name="keywords"
        content="abangkom, bangkom, bang kom, abangkom sleman, bangkom sleman, aplikasi pengembangan kompetensi, kompetensi ASN, BKPP, BKPP Sleman, Badan Kepegawaian Pendidikan dan Pelatihan (BKPP) Kabupaten Sleman, Kompetensi Sleman">
    <meta name="robots" content="index,follow">
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8">
    <meta name="language" content="N/A">
    <meta content="width=device-width, initial-scale=1, maximum-scale=1, shrink-to-fit=no" name="viewport">
    {{-- favicon --}}
    <link rel="shortcut icon" href="{{ asset('assets/img/favicon.ico') }}" type="image/x-icon">
    <title>BANG KOM | Sleman &mdash; {{ $title }}</title>

    <!-- General CSS Files -->
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css"
        integrity="sha384-ggOyR0iXCbMQv3Xipma34MD+dH/1fQ784/j6cY/iJTQUOhcWr7x9JvoRxT2MZw1T" crossorigin="anonymous">
    <link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.7.2/css/all.css"
        integrity="sha384-fnmOCqbTlWIlj8LyTjo7mOUStjsKC4pOpQbqyi7RrhN7udi9RwhKkMHpvLbHG9Sr" crossorigin="anonymous">

    <!-- CSS Libraries -->
    <link rel="stylesheet" href="{{ asset('node_modules/jqvmap/dist/jqvmap.min.css') }}">
    <link rel="stylesheet" href="{{ asset('node_modules/summernote/dist/summernote-bs4.css') }}">
    <link rel="stylesheet" href="{{ asset('node_modules/owl.carousel/dist/assets/owl.carousel.min.css') }}">
    <link rel="stylesheet" href="{{ asset('node_modules/owl.carousel/dist/assets/owl.theme.default.min.css') }}">
    <link rel="stylesheet" href="{{ asset('node_modules/bootstrap-social/bootstrap-social.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/css/show-password-toggle.css') }}">

    <!-- Template CSS -->
    <link rel="stylesheet" href="{{ asset('assets/css/style.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/css/components.css') }}">
    {!! NoCaptcha::renderJs('id') !!}

    @stack('css')
</head>

<body
    style="background-image: url({{ asset('assets/img/login_background.webp') }}); background-size: cover; background-repeat: no-repeat; background-position: center; ">
    <div id="app">
        <section class="section">
            <div class="container mt-5">
                <div class="row">
                    <div
                        class="col-12 col-sm-8 offset-sm-2 col-md-6 offset-md-3 col-lg-6 offset-lg-3 col-xl-4 offset-xl-4">
                        <div class="login-brand">
                            <h5 class="text-white">ABANGKOMANDAN</h5>
                        </div>

                        <div class="card card-primary">
                            <div class="card-header d-block mb-1">
                                <h4>Login</h4>
                            </div>
                            @if (session()->has('loginError'))
                                <div class="alert alert-danger alert-dismissible show fade mx-4">
                                    <div class="alert-body">
                                        <button class="close" data-dismiss="alert">
                                            <span>&times;</span>
                                        </button>
                                        {!! session('loginError') !!}
                                    </div>
                                </div>
                            @endif

                            <div class="card-body">
                                <form method="POST" action="{{ route('login') }}" class="needs-validation">
                                    @csrf

                                    <div class="form-group">
                                        <label for="user_id">User</label>
                                        <input id="user_id" placeholder="..User.." type="text" autocomplete="off"
                                            maxlength="18" class="form-control @error('user_id') is-invalid @enderror"
                                            name="user_id" tabindex="1" required autofocus
                                            value="{{ old('user_id') }}">
                                        @error('user_id')
                                            <div class="invalid-feedback">
                                                {{ $message }}
                                            </div>
                                        @enderror
                                    </div>

                                    <div class="form-group">
                                        <div class="d-block">
                                            <label for="password" class="control-label">Password</label>
                                        </div>

                                        <div class="input-group">
                                            <input tid="password" placeholder="..Password.." type="password"
                                                autocomplete="current-password" name="password"
                                                class="form-control rounded @error('password') is-invalid @enderror"
                                                spellcheck="false" autocorrect="off" autocapitalize="off" required>
                                            <button id="toggle-password" type="button" class="d-none"
                                                aria-label="Show password as plain text. Warning: this will display your password on the screen.">
                                            </button>
                                        </div>

                                        @error('password')
                                            <div class="invalid-feedback">
                                                {{ $message }}
                                            </div>
                                        @enderror
                                    </div>

                                    <div
                                        class="form-group{{ $errors->has('g-recaptcha-response') ? ' has-error' : '' }}">
                                        <div class="pull-center">
                                            {!! NoCaptcha::display() !!}
                                            @if ($errors->has('g-recaptcha-response'))
                                                <span class="help-block">
                                                    <strong class="text-danger">*
                                                        {{ $errors->first('g-recaptcha-response') }}</strong>
                                                </span>
                                            @endif
                                        </div>
                                    </div>

                                    <div class="form-group">
                                        <button type="submit" class="btn btn-primary btn-lg btn-block">
                                            Login
                                        </button>
                                    </div>
                                </form>
                            </div>
                        </div>
                        <div class="simple-footer text-white">
                            Copyright &copy; 2022 - {{ Carbon\Carbon::now()->year }}
                        </div>
                    </div>
                </div>
            </div>
        </section>
    </div>

    <meta http-equiv="refresh" content="600">
    <!-- General JS Scripts -->
    <script src="https://cdn.jsdelivr.net/npm/jquery@3.6.0/dist/jquery.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.7/umd/popper.min.js"
        integrity="sha384-UO2eT0CpHqdSJQ6hJty5KVphtPhzWj9WO1clHTMGa3JDZwrnQq4sF86dIHNDz0W1" crossorigin="anonymous">
    </script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/js/bootstrap.min.js"
        integrity="sha384-JjSmVgyd0p3pXB1rRibZUAYoIIy6OrQ6VrjIEaFf/nJGzIxFDsf4x0xIM+B07jRM" crossorigin="anonymous">
    </script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery.nicescroll/3.7.6/jquery.nicescroll.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/moment.js/2.24.0/moment.min.js"></script>
    <script src="{{ asset('assets/js/stisla.js') }}"></script>

    <!-- JS Libraies -->
    <script src="{{ asset('node_modules/jquery-sparkline/jquery.sparkline.min.js') }}"></script>
    <script src="{{ asset('node_modules/chart.js/dist/Chart.min.js') }}"></script>
    <script src="{{ asset('node_modules/owl.carousel/dist/owl.carousel.min.js') }}"></script>
    <script src="{{ asset('node_modules/summernote/dist/summernote-bs4.js') }}"></script>
    <script src="{{ asset('node_modules/chocolat/dist/js/jquery.chocolat.min.js') }}"></script>
    <script src="{{ asset('assets/js/show-password-toggle.min.js') }}"></script>


    <!-- Template JS File -->
    <script src="{{ asset('assets/js/scripts.js') }}"></script>
    <script src="{{ asset('assets/js/custom.js') }}"></script>

    @if (session('success'))
        <script src="https://unpkg.com/sweetalert2@7.18.0/dist/sweetalert2.all.js"></script>
        <script>
            Swal.fire('success', "{{ session('success') }}", 'success');
        </script>
    @endif

    @stack('js')

</body>

</html>
