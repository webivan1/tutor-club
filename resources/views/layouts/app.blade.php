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

    <link href="https://fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet">

    <!-- Styles -->
    <link href="{{ asset('css/app.css') }}" rel="stylesheet">

    @yield('script.head')
</head>
<body>
    <nav class="navbar navbar-expand-md navbar-light navbar-laravel">
        <div class="container">
            <a class="navbar-brand" href="{{ url('/') }}">
                {{ config('app.name', 'Laravel') }}
            </a>
            <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
            </button>

            <div class="collapse navbar-collapse" id="navbarSupportedContent">
                <!-- Left Side Of Navbar -->
                <ul class="navbar-nav mr-auto">

                </ul>

                @include('layouts._nav_right')
            </div>
        </div>
    </nav>

    <main id="app" class="app-content pb-4">
        <div class="container-fluid">
            <div class="header-line bg-primary row align-content-center">
                <div class="col-md-12">
                    <div class="container">
                        <div class="row justify-content-center">
                            <div class="col-md-9">
                                <div class="breadcrumb-color-white">
                                    @section('breadcrumbs')
                                        {{ Breadcrumbs::render() }}
                                    @show
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                @section('search')
                    <div class="col-md-12">
                        <div class="container">
                            <div class="row justify-content-center">
                                <div class="col-md-9">
                                    <search-category-component
                                        messages='{{ json_encode([
                                            'placeholder' => t('home.SearchCategoryField'),
                                            'button' => t('home.SearchCategoryButton'),
                                            'search' => route('category.search')
                                        ]) }}'
                                    ></search-category-component>
                                </div>
                            </div>
                        </div>
                    </div>
                @show
                <div class="col-md-12">
                    <div class="container">
                        <div class="row justify-content-center">
                            <div class="col-md-9">
                                @include('errors.flash_message')
                                @include('errors.list')
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="main-container">
            <div class="container">
                <div class="row justify-content-center">
                    <div class="col-md-9">
                        @yield('content')
                    </div>
                </div>
            </div>
        </div>
    </main>

    <footer class="footer">
        <div class="container">
            @TutorOnline
        </div>
    </footer>

    <!-- Scripts -->
    <script src="{{ asset('js/app.js') }}"></script>
    @yield('script.body')
</body>
</html>
