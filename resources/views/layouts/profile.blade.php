@extends('layouts.cabinet')

@section('nav-left')
    {{ Html::link(route('profile.home'), t('home.profileLink'), [
        'class' => 'list-group-item ' . (!Request::routeIs('profile.home') ?: 'active')
    ]) }}
    {{ Html::link(route('profile.tutor.home'), t('home.profileTutorLink'), [
        'class' => 'list-group-item ' . (!Request::routeIs('profile.tutor.*') ?: 'active')
    ]) }}
    @if (Auth::user()->can('access-advert'))
        {{ Html::link(route('cabinet.advert.index'), t('home.myAdvert'), [
            'class' => 'list-group-item ' . (!Request::routeIs('cabinet.advert.*') ?: 'active')
        ]) }}
    @endif
@endsection

@section('content')

@endsection