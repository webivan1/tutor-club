<!DOCTYPE html>
<html lang="{{ app()->getLocale() }}">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <!-- CSRF Token -->
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'Laravel') }} | Cabinet | @yield('title')</title>

    <link href="https://fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet">

    <!-- Styles -->
    <link href="{{ asset('css/app.css') }}" rel="stylesheet">

    @yield('script.head')
</head>
<body>
    <div class="bmd-layout-container bmd-drawer-f-l @hasSection('not-drawers') @else bmd-drawer-in @endif">
        <header class="bmd-layout-header">
            <nav class="navbar navbar-expand-md navbar-light navbar-laravel">
                <div class="container">
                    <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
                        <span class="navbar-toggler-icon"></span>
                    </button>

                    <div class="collapse navbar-collapse" id="navbarSupportedContent">

                        @hasSection('not-drawers')

                        @else
                            <!-- Left Side Of Navbar -->
                            <ul class="navbar-nav mr-auto">
                                <li class="nav-item">
                                    <a class="nav-link" href="#" role="button"  data-toggle="drawer" data-target="#dw-s1">
                                        <i class="material-icons">menu</i>
                                    </a>
                                </li>
                            </ul>
                        @endif

                        @include('layouts._nav_right')
                    </div>
                </div>
            </nav>
        </header>
        <div id="dw-s1" class="bmd-layout-drawer bg-faded">
            <header>
                <a class="navbar-brand">
                    {{ config('app.name', 'SiteName') }}
                </a>
            </header>
            <ul class="list-group">
                @yield('nav-left')
            </ul>
        </div>
        <main class="bmd-layout-content">
            <div class="container pt-3 pb-3">
                @section('breadcrumbs', Breadcrumbs::render())
                @yield('breadcrumbs')

                @include('errors.flash_message')
                @include ('errors.list')

                @yield('content')
            </div>
        </main>
    </div>

    <!-- Scripts -->
    <script src="{{ asset('js/app.js') }}" defer></script>
    @yield('script.body')
</body>
</html>
