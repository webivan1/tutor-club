<!DOCTYPE html>
<html lang="{{ app()->getLocale() }}">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <!-- CSRF Token -->
    <meta name="csrf-token" content="{{ csrf_token() }}" />
    <meta name="description" content="@yield('description')" />

    <title>@yield('title')</title>

    <!-- Styles -->
    <link href="{{ asset('fontawesome-free-5.0.13/web-fonts-with-css/css/fa-regular.min.css') }}" rel="stylesheet">
    <link href="{{ asset('fontawesome-free-5.0.13/web-fonts-with-css/css/fa-solid.min.css') }}" rel="stylesheet">
    <link href="{{ asset('fontawesome-free-5.0.13/web-fonts-with-css/css/fa-brands.min.css') }}" rel="stylesheet">
    <link href="{{ asset('fontawesome-free-5.0.13/web-fonts-with-css/css/fontawesome-all.min.css') }}" rel="stylesheet">
    <link href="{{ asset('css/app.css?t=' . time()) }}" rel="stylesheet">

    @yield('script.head')
</head>
<body self-user-id="{{ \Auth::id() }}" data-url="{{ route('home') }}">


    @yield('body')

    <!-- Scripts -->
    <script src="{{ asset('js/app.js?t=' . time()) }}"></script>
    <script src="{{ asset('scripts/bootstrap/bootstrap.bundle.min.js') }}"></script>
    @yield('script.body')
</body>
</html>