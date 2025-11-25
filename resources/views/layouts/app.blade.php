<!DOCTYPE html>
<html lang="en">

<head>
    @include('partials._head')
    <style>
        .redtitle {
            color: #73c94c !important;
        }

        .redbackground {
            background-color: #73c94c !important;
            color: white;
        }
    </style>
</head>

<body>

    @include('partials._svg-sprite')
    @include('partials._preloader')

    @include('partials._offcanvas-cart')
    @include('partials._offcanvas-search')

    @include('partials._header')

    <div class="container-fluid">
        @yield('content')
    </div>


    @include('partials._footer')
    @include('partials._footer-bottom')

    {{-- JS local files for faster loading --}}
    <script src="{{ asset('vendor/swiper-bundle.min.js') }}"></script>
    <script src="{{ asset('vendor/bootstrap.bundle.min.js') }}"></script>

    <script src="{{ asset('js/jquery-1.11.0.min.js') }}"></script>
    <script src="{{ asset('js/plugins.js') }}"></script>
    <script src="{{ asset('js/script.js') }}"></script>

    @stack('scripts')
</body>

</html>