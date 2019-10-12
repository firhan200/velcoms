<!doctype html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <title>@yield('title')</title>

        <!-- CSS -->
        <link rel="stylesheet" href="{{ asset('libs/bootstrap/css/bootstrap.min.css') }}">
        <link rel="stylesheet" href="{{ asset('css/main.css') }}">
    </head>
    <body>
        <!-- Navigation -->
        @include('front.layouts.navigation')

        <!-- Main Content -->
        @yield('content')

        <!-- Footer -->
        @include('front.layouts.footer')

        <!-- Javascript -->
        <script type="text/javascript" src="{{ asset('js/jquery.min.js') }}"></script>
        <script type="text/javascript" src="{{ asset('libs/bootstrap/js/bootstrap.min.js') }}"></script>
        <script type="text/javascript" src="{{ asset('js/main.js') }}"></script>
    </body>
</html>
