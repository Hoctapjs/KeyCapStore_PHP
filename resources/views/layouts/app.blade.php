<!DOCTYPE html>
<html lang="en">

<head>
    @include('partials._head')
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

    {{-- JS CDN + file trong public --}}
    <script src="https://cdn.jsdelivr.net/npm/swiper@9/swiper-bundle.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha3/dist/js/bootstrap.bundle.min.js"
        integrity="sha384-ENjdO4Dr2bkBIFxQpeoTz1HIcje39Wm4jDKdf19U8gI4ddQ3GYNS7NTKfAdVQSZe"
        crossorigin="anonymous"></script>

    <script src="{{ asset('js/jquery-1.11.0.min.js') }}"></script>
    <script src="{{ asset('js/plugins.js') }}"></script>
    <script src="{{ asset('js/script.js') }}"></script>

    @stack('scripts')
</body>

</html>