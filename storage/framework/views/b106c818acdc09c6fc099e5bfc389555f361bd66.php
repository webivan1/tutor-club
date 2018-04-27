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

    <?php if($profile->isActive()): ?>
        <div class="text-right">
            <?php echo e(Html::link(route('profile.tutor.form'), 'Редактировать профиль', [
                'class' => 'btn btn-primary mb-2'
            ])); ?>

        </div>

        <div class="card mb-4">
            <div class="card-body">
                <div class="alert alert-success mb-0">
                    <div>
                        Ваш профиль активен!
                        Вы можете создавать объявления на сайте
                    </div>

                    <?php echo e(Html::link(route('cabinet.advert.index'), 'Создать объявление', [
                        'class' => 'btn btn-success btn-raised mt-1'
                    ])); ?>

                </div>
            </div>
        </div>
    <?php endif; ?>

    <?php if($profile->isDisabled()): ?>
        <?php if($profile->isVerified()): ?>
            <div class="text-center mb-3">
                <?php echo e(Html::link(route('profile.tutor.moderation'), 'Отправить на модерацию', [
                    'class' => 'btn btn-lg btn-success'
                ])); ?>

            </div>
        <?php else: ?>
            <div class="text-center mb-3">
                <?php echo e(Html::link(route('profile.tutor.verify.send'), 'Подтвердить телефон', [
                    'class' => 'btn btn-lg btn-success ml-3'
                ])); ?>

            </div>
        <?php endif; ?>

        <div class="text-right">
            <?php echo e(Html::link(route('profile.tutor.form'), 'Редактировать профиль', [
                'class' => 'btn btn-primary mb-2'
            ])); ?>

        </div>

        <div class="card mb-4">
            <div class="card-body">
                <div class="alert alert-warning mb-0">
                    Ваш профиль пока не активен
                </div>
            </div>
        </div>
    <?php endif; ?>

    <?php if($profile->isModeration()): ?>
        <div class="card mb-4">
            <div class="card-body">
                <div class="alert alert-warning mb-0">
                    Ваш профиль находится на модерации, его изменять пока нельзя
                </div>
            </div>
        </div>
    <?php endif; ?>

    <?php if($profile->isPending()): ?>
        <div class="text-right">
            <?php echo e(Html::link(route('profile.tutor.form'), 'Редактировать профиль', [
                'class' => 'btn btn-primary mb-2'
            ])); ?>

        </div>

        <div class="card mb-4">
            <div class="card-body">
                <div class="alert alert-warning mb-0">
                    Нада исправить следующие ошибки:
                    <?php echo e($profile->comment); ?>

                </div>
            </div>
        </div>
    <?php endif; ?>

    <div class="row">
        <div class="col-md-auto">
            <?php echo e(Html::image($profile->image->file_path . '?t=' . time(), 'Аватар', [
                'class' => 'img-thumbnail'
            ])); ?>

        </div>
        <div class="col">
            <div class="card">
                <ul class="list-group">
                    <li class="list-group-item">
                        <b>Имя:</b> <?php echo e(Auth::user()->name); ?>

                    </li>
                    <li class="list-group-item">
                        <b>Email:</b> <?php echo e(Auth::user()->email); ?>

                    </li>
                    <li class="list-group-item">
                        <b>Телефон:</b> <?php echo e($profile->country_code); ?> <?php echo e($profile->phone); ?>

                        <?php if(!$profile->isVerified()): ?>
                            <?php echo e(Html::link(route('profile.tutor.verify.send'), 'Подтвердить телефон', [
                                'class' => 'btn btn-success ml-3'
                            ])); ?>

                        <?php endif; ?>
                    </li>
                    <li class="list-group-item">
                        <b>Пол:</b> <?php echo e($profile->genders()[$profile->gender]); ?>

                    </li>
                </ul>
                <?php if($content): ?>
                    <div class="card-body">
                        <h5 class="text-muted">Краткое описание:</h5>
                        <p><?php echo e($content->description); ?></p>

                        <h5 class="text-muted mt-3">Полное описание:</h5>
                        <?php echo $content->content; ?>

                    </div>
                <?php endif; ?>
            </div>
        </div>
    </div>
</div>
