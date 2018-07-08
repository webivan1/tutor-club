@extends('layouts.layout')

@section('body')
    <nav class="navbar navbar-expand-md bg-primary navbar-dark position-relative"> <!-- navbar-light bg-white -->
        <div class="container">
            <a class="navbar-brand" href="{{ route('home') }}">
                {{ config('app.name') }}
            </a>
            <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
            </button>

            <div class="collapse navbar-collapse" id="navbarSupportedContent">
                <!-- Left Side Of Navbar -->
                <ul class="navbar-nav">
                    <li class="nav-item">
                        {{ Html::link(route('category.list'), t('Tutor search'), [
                            'class' => 'nav-link ' . (!Request::routeIs('category.list') ?: 'active')
                        ]) }}
                    </li>
                    <li class="nav-item">
                        {{ Html::link(route('profile.tutor.home'), t('Become a tutor'), [
                            'class' => 'nav-link ' . (!Request::routeIs('profile.tutor.home') ?: 'active')
                        ]) }}
                    </li>
                </ul>

                <div class="ml-2 app-vue">
                    <search-category-component
                        messages='{{ json_encode([
                            'placeholder' => t('home.SearchCategoryField'),
                            'button' => t('home.SearchCategoryButton'),
                            'search' => route('category.search')
                        ]) }}'
                    ></search-category-component>
                </div>

                @include('layouts._nav_right', [
                    'theme' => 'dark'
                ])
            </div>
        </div>
    </nav>

    <main class="app-vue app-content pb-4">

        <div class="container-fluid px-0 @hasSection('top-content') top-content @endif">
            <div class="header-line">
                <div class="container">
                    <div class="row justify-content-center">
                        <div class="col-md-12">
                            @include('errors.flash_message')
                            @include ('errors.list')

                            <div>
                                @section('breadcrumbs')
                                    {{ Breadcrumbs::render() }}
                                @show
                            </div>

                            @hasSection('h1')
                                <h1 class="text-secondary @hasSection('breadcrumbs') mt-3 @endif">@yield('h1')</h1>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="main-container">
            <div class="container">
                <div class="row justify-content-center">
                    <div class="@section('width-content') col-md-12 @show">
                        @yield('content')
                    </div>
                </div>
            </div>
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
@endsection