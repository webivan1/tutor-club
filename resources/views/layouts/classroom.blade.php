@extends('layouts.layout')

@section('body')
    <div class="vh-100">
        <div class="flex-vertical">
            <nav class="navbar navbar-expand-md navbar-dark bg-indigo position-relative">
                <div class="container">
                    <a class="navbar-brand text-white" href="{{ route('home') }}">
                        {{ config('app.name') }}
                    </a>
                    <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
                        <span class="navbar-toggler-icon"></span>
                    </button>

                    <div class="collapse navbar-collapse" id="navbarSupportedContent">
                        <!-- Left Side Of Navbar -->
                        <ul class="navbar-nav">
                            <li class="nav-item">
                                {{ Html::link(route('profile.home'), t('home.profileLink'), [
                                    'class' => 'nav-link ' . (!Request::routeIs('profile.home') ?: 'active')
                                ]) }}
                            </li>
                            <li class="nav-item">
                                {{ Html::link(route('profile.tutor.home'), t('home.profileTutorLink'), [
                                    'class' => 'nav-link ' . (!Request::routeIs('profile.tutor.*') ?: 'active')
                                ]) }}
                            </li>
                            <li class="nav-item">
                                @if (Auth::user()->can('access-advert'))
                                    {{ Html::link(route('cabinet.advert.index'), t('home.myAdvert'), [
                                        'class' => 'nav-link ' . (!Request::routeIs('cabinet.advert.*') ?: 'active')
                                    ]) }}
                                @endif
                            </li>

                            @yield('nav-left')
                        </ul>

                        @include('layouts._nav_right', [
                            'theme' => 'dark'
                        ])
                    </div>
                </div>
            </nav>

            <div class="flex-auto-height">
                <div class="container-fluid h-100">
                    <div class="app-vue h-100">
                        @yield('content')
                    </div>
                </div>
            </div>
        </div>
    </div>

    @auth
        <div class="app-vue">
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
        </div>
    @endauth
@endsection