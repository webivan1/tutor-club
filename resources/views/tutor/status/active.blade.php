<?php

use App\Entity\TutorProfile;
use App\Entity\ContentProfile;

$content = $profile->content()->where('lang', app()->getLocale())->first();

/**
 * @var TutorProfile $profile
 * @var ContentProfile $content
 */

?>

<div>

    @if($profile->isActive())
        <div class="text-right">
            {{ Html::link(route('profile.tutor.form'), t('Edit profile'), [
                'class' => 'btn btn-raised btn-info mb-2'
            ]) }}
        </div>

        <div class="card mb-4">
            <div class="card-body">
                <div class="alert alert-success mb-0">
                    <div>
                        {{ t('Your profile is active') }}! <br />
                        {{ t('You can create ads on the site') }}
                    </div>

                    {{ Html::link(route('cabinet.advert.index'), t('Create an ad'), [
                        'class' => 'btn btn-success btn-raised mt-1'
                    ]) }}
                </div>
            </div>
        </div>
    @endif

    @if($profile->isDisabled())
        @if ($profile->isVerified())
            <div class="text-center mb-3">
                {{ Html::link(route('profile.tutor.moderation'), t('Submit to moderation'), [
                    'class' => 'btn btn-lg btn-success'
                ]) }}
            </div>
        @else
            <div class="text-center mb-3">
                {{ Html::link(route('profile.tutor.verify.send'), t('Confirm phone'), [
                    'class' => 'btn btn-lg btn-success ml-3'
                ]) }}
            </div>
        @endif

        <div class="text-right">
            {{ Html::link(route('profile.tutor.form'), t('Edit profile'), [
                'class' => 'btn btn-primary mb-2'
            ]) }}
        </div>

        <div class="card mb-4">
            <div class="card-body">
                <div class="alert alert-warning mb-0">
                    {{ t('Your profile is not active') }}
                </div>
            </div>
        </div>
    @endif

    @if($profile->isModeration())
        <div class="card mb-4">
            <div class="card-body">
                <div class="alert alert-warning mb-0">
                    {{ t('Your profile is on moderation, you can not change it yet') }}
                </div>
            </div>
        </div>
    @endif

    @if($profile->isPending())
        <div class="text-right">
            {{ Html::link(route('profile.tutor.form'), t('Edit profile'), [
                'class' => 'btn btn-primary mb-2'
            ]) }}
        </div>

        <div class="card mb-4">
            <div class="card-body">
                <div class="alert alert-warning mb-0">
                    {{ t('It is necessary to correct the following errors') }}: <br />
                    {{ $profile->comment }}
                </div>
            </div>
        </div>
    @endif

    <div class="row">
        <div class="col-md-auto">
            {{ Html::image($profile->image->file_path . '?t=' . time(), t('Avatar'), [
                'class' => 'img-thumbnail'
            ]) }}
        </div>
        <div class="col">
            <div class="card">
                <ul class="list-group">
                    <li class="list-group-item">
                        <b>{{ t('Name') }}:</b> {{ Auth::user()->name }}
                    </li>
                    <li class="list-group-item">
                        <b>Email:</b> {{ Auth::user()->email }}
                    </li>
                    <li class="list-group-item">
                        <b>{{ t('Phone') }}:</b> {{ $profile->country_code }} {{ $profile->phone }}
                        @if (!$profile->isVerified())
                            {{ Html::link(route('profile.tutor.verify.send'), 'Подтвердить телефон', [
                                'class' => 'btn btn-success ml-3'
                            ]) }}
                        @endif
                    </li>
                    <li class="list-group-item">
                        <b>{{ t('Gender') }}:</b> {{ $profile->genders()[$profile->gender] }}
                    </li>
                </ul>
                @if ($content)
                    <div class="card-body">
                        <h5 class="text-muted">{{ t('smallDescription') }}:</h5>
                        <p>{{ $content->description }}</p>

                        <h5 class="text-muted mt-3">{{ t('largeDescription') }}:</h5>
                        {!! $content->content !!}
                    </div>
                @endif
            </div>
        </div>
    </div>
</div>
