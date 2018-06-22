@extends('layouts.layout')

@section('body')
    <nav class="navbar navbar-expand-md navbar-light bg-white">
        <div class="container">
            <a class="navbar-brand" href="{{ route('home') }}">
                {{ config('app.name') }}
            </a>
            <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
            </button>

            <div class="collapse navbar-collapse" id="navbarSupportedContent">
                <!-- Left Side Of Navbar -->
                <ul class="navbar-nav mr-auto">
                    <li class="nav-item">
                        <a href="{{ route('category.list') }}" class="nav-link">{{ t('Tutor search') }}</a>
                    </li>
                    <li class="nav-item">
                        <a href="{{ route('profile.tutor.home') }}" class="nav-link">{{ t('Become a tutor') }}</a>
                    </li>
                </ul>

                @include('layouts._nav_right')
            </div>
        </div>
    </nav>

    <main class="app-vue app-content pb-4">
        <div class="container-fluid">
            <div class="header-line bg-primary row align-content-center">
                <div class="col-md-12">
                    <div class="container">
                        <div class="row justify-content-center">
                            <div class="@section('width-content') col-md-9 @show">
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
                                <div class="@section('width-content') col-md-9 @show">
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
                            <div class="@section('width-content') col-md-9 @show">
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
                    <div class="@section('width-content') col-md-9 @show">
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



{{--<!DOCTYPE html>--}}
{{--<html lang="{{ app()->getLocale() }}">--}}
{{--<head>--}}
    {{--<meta charset="utf-8">--}}
    {{--<meta http-equiv="X-UA-Compatible" content="IE=edge">--}}
    {{--<meta name="viewport" content="width=device-width, initial-scale=1">--}}

    {{--<!-- CSRF Token -->--}}
    {{--<meta name="csrf-token" content="{{ csrf_token() }}" />--}}
    {{--<meta name="description" content="@yield('description')" />--}}

    {{--<title>@yield('title')</title>--}}

    {{--<!-- Styles -->--}}
    {{--<link href="{{ asset('fontawesome-free-5.0.13/web-fonts-with-css/css/fa-regular.min.css') }}" rel="stylesheet">--}}
    {{--<link href="{{ asset('fontawesome-free-5.0.13/web-fonts-with-css/css/fa-solid.min.css') }}" rel="stylesheet">--}}
    {{--<link href="{{ asset('fontawesome-free-5.0.13/web-fonts-with-css/css/fa-brands.min.css') }}" rel="stylesheet">--}}
    {{--<link href="{{ asset('fontawesome-free-5.0.13/web-fonts-with-css/css/fontawesome-all.min.css') }}" rel="stylesheet">--}}
    {{--<link href="{{ asset('css/app.css') }}" rel="stylesheet">--}}

    {{--@yield('script.head')--}}
{{--</head>--}}
{{--<body self-user-id="{{ \Auth::id() }}" data-url="{{ route('home') }}">--}}
    {{--<nav class="navbar navbar-expand-md navbar-dark bg-dark">--}}
        {{--<div class="container">--}}
            {{--<a class="navbar-brand" href="{{ route('home') }}">--}}
                {{--{{ config('app.name') }}--}}
            {{--</a>--}}
            {{--<button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">--}}
                {{--<span class="navbar-toggler-icon"></span>--}}
            {{--</button>--}}

            {{--<div class="collapse navbar-collapse" id="navbarSupportedContent">--}}
                {{--<!-- Left Side Of Navbar -->--}}
                {{--<ul class="navbar-nav mr-auto">--}}
                    {{--<li class="nav-item">--}}
                        {{--<a href="{{ route('category.list') }}" class="nav-link">{{ t('Tutor search') }}</a>--}}
                    {{--</li>--}}
                    {{--<li class="nav-item">--}}
                        {{--<a href="{{ route('profile.tutor.home') }}" class="nav-link">{{ t('Become a tutor') }}</a>--}}
                    {{--</li>--}}
                    {{--<li class="nav-item">--}}
                        {{--<a class="nav-link">{{ t('News') }}</a>--}}
                    {{--</li>--}}
                    {{--<li class="nav-item">--}}
                        {{--<a class="nav-link">{{ t('Contacts') }}</a>--}}
                    {{--</li>--}}
                {{--</ul>--}}

                {{--@include('layouts._nav_right')--}}
            {{--</div>--}}
        {{--</div>--}}
    {{--</nav>--}}

    {{--<main class="app-vue app-content pb-4">--}}
        {{--<div class="container-fluid">--}}
            {{--<div class="header-line bg-primary row align-content-center">--}}
                {{--<div class="col-md-12">--}}
                    {{--<div class="container">--}}
                        {{--<div class="row justify-content-center">--}}
                            {{--<div class="@section('width-content') col-md-9 @show">--}}
                                {{--<div class="breadcrumb-color-white">--}}
                                    {{--@section('breadcrumbs')--}}
                                        {{--{{ Breadcrumbs::render() }}--}}
                                    {{--@show--}}
                                {{--</div>--}}
                            {{--</div>--}}
                        {{--</div>--}}
                    {{--</div>--}}
                {{--</div>--}}
                {{--@section('search')--}}
                    {{--<div class="col-md-12">--}}
                        {{--<div class="container">--}}
                            {{--<div class="row justify-content-center">--}}
                                {{--<div class="@section('width-content') col-md-9 @show">--}}
                                    {{--<search-category-component--}}
                                        {{--messages='{{ json_encode([--}}
                                            {{--'placeholder' => t('home.SearchCategoryField'),--}}
                                            {{--'button' => t('home.SearchCategoryButton'),--}}
                                            {{--'search' => route('category.search')--}}
                                        {{--]) }}'--}}
                                    {{--></search-category-component>--}}
                                {{--</div>--}}
                            {{--</div>--}}
                        {{--</div>--}}
                    {{--</div>--}}
                {{--@show--}}
                {{--<div class="col-md-12">--}}
                    {{--<div class="container">--}}
                        {{--<div class="row justify-content-center">--}}
                            {{--<div class="@section('width-content') col-md-9 @show">--}}
                                {{--@include('errors.flash_message')--}}
                                {{--@include('errors.list')--}}
                            {{--</div>--}}
                        {{--</div>--}}
                    {{--</div>--}}
                {{--</div>--}}
            {{--</div>--}}
        {{--</div>--}}

        {{--<div class="main-container">--}}
            {{--<div class="container">--}}
                {{--<div class="row justify-content-center">--}}
                    {{--<div class="@section('width-content') col-md-9 @show">--}}
                        {{--@yield('content')--}}
                    {{--</div>--}}
                {{--</div>--}}
            {{--</div>--}}
        {{--</div>--}}

        {{--@auth--}}
            {{--<chat--}}
                {{--user="{{ \Auth::id() }}"--}}
                {{--data-json='{{ json_encode([--}}
                    {{--'prependUrl' => route('home'),--}}
                    {{--'messages' => [--}}
                        {{--'heading' => t('Chat'),--}}
                        {{--'open' => t('Open'),--}}
                        {{--'close' => t('Close'),--}}
                        {{--'PrevToDialogs' => t('Prev to dialogs')--}}
                    {{--]--}}
                {{--]) }}'--}}
            {{--></chat>--}}
        {{--@endauth--}}
    {{--</main>--}}

    {{--@include('layouts._footer')--}}

    {{--<!-- Scripts -->--}}
    {{--<script src="{{ asset('js/app.js') }}"></script>--}}
    {{--@yield('script.body')--}}
{{--</body>--}}
{{--</html>--}}
