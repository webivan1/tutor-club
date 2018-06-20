<!DOCTYPE html>
<html lang="{{ app()->getLocale() }}">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <!-- CSRF Token -->
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'Laravel') }} | Cabinet | Classroom | @yield('title')</title>

    <link href="https://fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet">

    <!-- Styles -->
    <link href="{{ asset('css/app.css') }}" rel="stylesheet">

    @yield('script.head')
</head>
<body self-user-id="{{ \Auth::id() }}" data-url="{{ route('home') }}">
    <nav class="navbar navbar-expand-md navbar-dark bg-dark">
        <div class="container-fluid">
            <a class="navbar-brand" href="{{ route('home') }}">
                {{ config('app.name') }}
            </a>
            <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
            </button>

            <div class="collapse navbar-collapse" id="navbarSupportedContent">
                <!-- Left Side Of Navbar -->
                <ul class="navbar-nav mr-auto">
                    {{-- <li class="nav-item">
                        <a class="nav-link">{{ t('Contacts') }}</a>
                    </li> --}}
                </ul>

                @include('layouts._nav_right')
            </div>
        </div>
    </nav>

    <main class="app-vue app-content pb-4">
        <div class="container-fluid pt-4">
            @yield('content')
        </div>

        @auth
            <chat
                user="{{ \Auth::id() }}"
                data-json='{{ json_encode([
                    'prependUrl' => route('home'),
                    'messages' => [
                        'heading' => t('Chat'),
                        'open' => t('Open'),
                        'close' => t('Close'),
                        'PrevToDialogs' => t('Prev to dialogs')
                    ]
                ]) }}'
            ></chat>
        @endauth
    </main>

    @include('layouts._footer')

    <!-- Scripts -->
    <script src="{{ asset('js/app.js') }}"></script>
    <script defer src="/fontawesome-free-5.0.13/svg-with-js/js/fontawesome-all.js"></script>
    @yield('script.body')
</body>
</html>
