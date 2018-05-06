@extends('layouts.profile')

@section('content')
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">{{ t('Welcome :name', ['name' => \Auth::user()->name]) }}</div>
                <div class="card-body">
                    <div class="list-group">
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
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
