<?php

use App\Entity\TutorProfile;
use App\Entity\ContentProfile;

$content = $profile->content()->where('lang', app()->getLocale())->first();

/**
 * @var TutorProfile $profile
 * @var ContentProfile $content
 */

?>

<div class="container">

    @if($profile->isActive())
        <div class="text-right">
            {{ Html::link(route('profile.tutor.form'), 'Редактировать профиль', [
                'class' => 'btn btn-primary mb-2'
            ]) }}
        </div>

        <div class="card mb-4">
            <div class="card-body">
                <div class="alert alert-success mb-0">
                    <div>
                        Ваш профиль активен!
                        Вы можете создавать объявления на сайте
                    </div>

                    {{ Html::link(route('cabinet.advert.index'), 'Создать объявление', [
                        'class' => 'btn btn-success btn-raised mt-1'
                    ]) }}
                </div>
            </div>
        </div>
    @endif

    @if($profile->isDisabled())
        @if ($profile->isVerified())
            <div class="text-center mb-3">
                {{ Html::link(route('profile.tutor.moderation'), 'Отправить на модерацию', [
                    'class' => 'btn btn-lg btn-success'
                ]) }}
            </div>
        @else
            <div class="text-center mb-3">
                {{ Html::link(route('profile.tutor.verify.send'), 'Подтвердить телефон', [
                    'class' => 'btn btn-lg btn-success ml-3'
                ]) }}
            </div>
        @endif

        <div class="text-right">
            {{ Html::link(route('profile.tutor.form'), 'Редактировать профиль', [
                'class' => 'btn btn-primary mb-2'
            ]) }}
        </div>

        <div class="card mb-4">
            <div class="card-body">
                <div class="alert alert-warning mb-0">
                    Ваш профиль пока не активен
                </div>
            </div>
        </div>
    @endif

    @if($profile->isModeration())
        <div class="card mb-4">
            <div class="card-body">
                <div class="alert alert-warning mb-0">
                    Ваш профиль находится на модерации, его изменять пока нельзя
                </div>
            </div>
        </div>
    @endif

    @if($profile->isPending())
        <div class="text-right">
            {{ Html::link(route('profile.tutor.form'), 'Редактировать профиль', [
                'class' => 'btn btn-primary mb-2'
            ]) }}
        </div>

        <div class="card mb-4">
            <div class="card-body">
                <div class="alert alert-warning mb-0">
                    Нада исправить следующие ошибки:
                    {{ $profile->comment }}
                </div>
            </div>
        </div>
    @endif

    <div class="row">
        <div class="col-md-auto">
            {{ Html::image($profile->image->file_path . '?t=' . time(), 'Аватар', [
                'class' => 'img-thumbnail'
            ]) }}
        </div>
        <div class="col">
            <div class="card">
                <ul class="list-group">
                    <li class="list-group-item">
                        <b>Имя:</b> {{ Auth::user()->name }}
                    </li>
                    <li class="list-group-item">
                        <b>Email:</b> {{ Auth::user()->email }}
                    </li>
                    <li class="list-group-item">
                        <b>Телефон:</b> {{ $profile->country_code }} {{ $profile->phone }}
                        @if (!$profile->isVerified())
                            {{ Html::link(route('profile.tutor.verify.send'), 'Подтвердить телефон', [
                                'class' => 'btn btn-success ml-3'
                            ]) }}
                        @endif
                    </li>
                    <li class="list-group-item">
                        <b>Пол:</b> {{ $profile->genders()[$profile->gender] }}
                    </li>
                </ul>
                @if ($content)
                    <div class="card-body">
                        <h5 class="text-muted">Краткое описание:</h5>
                        <p>{{ $content->description }}</p>

                        <h5 class="text-muted mt-3">Полное описание:</h5>
                        {!! $content->content !!}
                    </div>
                @endif
            </div>
        </div>
    </div>
</div>
