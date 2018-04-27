@extends('layouts.cabinet')

@section('not-drawers', true)

@section('content')

    @if (request('advert'))
        @if (request('advert')->isActive())
            <div class="alert alert-success">
                {{ t('home.yourAdvertIsActive') }}
            </div>
        @endif
        @if (request('advert')->isDraft())
            <div class="alert alert-secondary">
                {{ t('home.yourAdvertIsDraft') }}
            </div>
        @endif
        @if (request('advert')->isDisabled())
            <div class="alert alert-secondary">
                {{ t('home.yourAdvertIsDisabled') }}
            </div>
        @endif
        @if (request('advert')->isWait())
            <div class="alert alert-secondary">
                {{ t('home.yourAdvertIsWait') }}
            </div>
        @endif
        @if (request('advert')->isModeration())
            <div class="alert alert-warning">
                {{ t('home.yourAdvertIsModeration') }}
            </div>
        @endif
    @endif

    <div class="mb-5">
        <ul class="nav nav-tabs bg-dark">
            <li class="nav-item">
                {{ Html::link(route('cabinet.advert.update', request('advert')), t('home.editAdvertInfo'), [
                    'class' => 'nav-link ' . (!Request::routeIs('cabinet.advert.update') ?: 'active')
                ]) }}
            </li>
            <li class="nav-item">
                {{ Html::link(route('cabinet.advert.update.prices', request('advert')), t('home.editAdvertPrices'), [
                    'class' => 'nav-link ' . (!Request::routeIs('cabinet.advert.update.prices') ?: 'active')
                ]) }}
            </li>
            <li class="nav-item">
                {{ Html::link(route('cabinet.advert.update.files', request('advert')), t('home.editAdvertFiles'), [
                    'class' => 'nav-link ' . (!Request::routeIs('cabinet.advert.update.files') ?: 'active')
                ]) }}
            </li>
            <li class="nav-item">
                {{ Html::link(route('cabinet.advert.update.attribute', request('advert')), t('home.editAdvertAttribute'), [
                    'class' => 'nav-link ' . (!Request::routeIs('cabinet.advert.update.attribute') ?: 'active')
                ]) }}
            </li>
            @if (request('advert') && request('advert')->accessSendToModeration())
                <li class="nav-item">
                    {{ Html::link(route('cabinet.advert.moderation', request('advert')), t('home.sendToModerationAdvert'), [
                        'class' => 'text-success nav-link ' . (!Request::routeIs('cabinet.advert.moderation') ?: 'active'),
                        'onclick' => 'return confirm("' . t('home.AreYouSure') . '");'
                    ]) }}
                </li>
            @endif
        </ul>
    </div>

@endsection