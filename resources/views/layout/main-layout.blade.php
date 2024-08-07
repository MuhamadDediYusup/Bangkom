{{-- @dd(Auth::user()) --}}

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta content="width=device-width, initial-scale=1, maximum-scale=1, shrink-to-fit=no" name="viewport">
    <meta name="title" content="ABANG KOM | Aplikasi Pengembangan Kompetensi ASN di Kabupaten">
    <meta name="description"
        content="Aplikasi Pengembangan Kompetensi ASN di Sleman (Abangkomandan) digunakan untuk meningkatkan pelayanan dan pengelolaan pengembangan kompetensi ASN yang dikelola oleh Badan Kepegawaian, Pendidikan dan Pelatihan (BKPP) Kabupaten Sleman.">
    <meta name="keywords"
        content="abangkom, bangkom, bang kom, abangkom sleman, bangkom sleman, aplikasi pengembangan kompetensi, kompetensi ASN, BKPP, BKPP Sleman, Badan Kepegawaian Pendidikan dan Pelatihan (BKPP) Kabupaten Sleman, Kompetensi Sleman">
    <meta name="robots" content="index,follow">
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8">
    <meta name="language" content="N/A">


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

    <link rel="stylesheet" href="{{ asset('node_modules/bootstrap-daterangepicker/daterangepicker.css') }}">
    {{--
    <link rel="stylesheet"
        href="{{ asset('node_modules/bootstrap-colorpicker/dist/css/bootstrap-colorpicker.min.css') }}"> --}}
    <link rel="stylesheet" href="{{ asset('node_modules/select2/dist/css/select2.min.css') }}">
    {{--
    <link rel="stylesheet" href="{{ asset('node_modules/jquery-selectric/selectric.css') }}"> --}}
    <link rel="stylesheet" href="{{ asset('node_modules/bootstrap-timepicker/css/bootstrap-timepicker.min.css') }}">
    <link rel="stylesheet" href="{{ asset('node_modules/bootstrap-tagsinput/dist/bootstrap-tagsinput.css') }}">

    <!-- Template CSS -->
    <link rel="stylesheet" href="{{ asset('assets/css/style.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/css/custom.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/css/components.css') }}">
    <link rel="stylesheet"
        href="https://cdn.jsdelivr.net/npm/@fortawesome/fontawesome-free@6.1.2/css/fontawesome.min.css">

    {{-- datatables --}}
    <link rel="stylesheet" href="{{ asset('assets/lib/datatables/datatables.min.css') }}">

    @stack('css')
</head>

<body>
    <div id="app">
        <div class="main-wrapper">
            <div class="navbar-bg"></div>
            <nav class="navbar navbar-expand-lg main-navbar">
                @include('partials.top-navbar')
            </nav>
            <div class="main-sidebar sidebar-style-2">
                @include('partials.sidebar')
            </div>

            <!-- Main Content -->
            <div class="main-content">
                <section class="section">
                    @yield('content')
                </section>
            </div>
            <footer class="main-footer">
                @include('partials.footer')
            </footer>
        </div>
    </div>

    <a class="scroll-top btn btn-dark" style="display: flex;">
        <i class="fa-sharp fa-solid fa-chevron-up"></i>
    </a>

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
    {{-- <script src="{{ asset('node_modules/cleave-js/dist/cleave.min.js') }}"></script> --}}
    {{-- <script src="{{ asset('node_modules/cleave-js/dist/addons/cleave-phone.us.js') }}"></script> --}}
    <script src="{{ asset('node_modules/jquery-pwstrength/jquery.pwstrength.min.js') }}"></script>
    <script src="{{ asset('node_modules/bootstrap-daterangepicker/daterangepicker.js') }}"></script>
    <script src="{{ asset('node_modules/bootstrap-colorpicker/dist/js/bootstrap-colorpicker.min.js') }}"></script>
    <script src="{{ asset('node_modules/bootstrap-timepicker/js/bootstrap-timepicker.min.js') }}"></script>
    <script src="{{ asset('assets/js/library.js') }}"></script>
    <script src="{{ asset('node_modules/bootstrap-tagsinput/dist/bootstrap-tagsinput.min.js') }}"></script>
    <script src="{{ asset('node_modules/select2/dist/js/select2.full.min.js') }}"></script>
    {{-- <script src="{{ asset('node_modules/jquery-selectric/jquery.selectric.min.js') }}"></script> --}}
    {{-- <script src="https://kit.fontawesome.com/8af041ab9e.js" crossorigin="anonymous"></script> --}}
    <script src="{{ asset('assets/lib/fontawsome.js') }}"></script>

    <!-- Template JS File -->
    <script src="{{ asset('assets/js/scripts.js') }}"></script>
    <script src="{{ asset('assets/js/custom.js') }}"></script>
    <script src="{{ asset('assets/js/scroll.js') }}"></script>

    {{-- datatables --}}
    <script src="{{ asset('assets/lib/datatables/datatables.min.js') }}"></script>
    <script src="{{ asset('assets/lib/datatables/modules-datatables.js') }}"></script>

    @stack('js')
    @include('partials.page-preload')

</body>

</html>