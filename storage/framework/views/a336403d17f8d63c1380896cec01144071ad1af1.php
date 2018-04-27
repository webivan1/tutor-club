<?php $__env->startSection('not-drawers', true); ?>

<?php $__env->startSection('content'); ?>

    <?php if(request('advert')): ?>
        <?php if(request('advert')->isActive()): ?>
            <div class="alert alert-success">
                <?php echo e(__('home.yourAdvertIsActive')); ?>

            </div>
        <?php endif; ?>
        <?php if(request('advert')->isDraft()): ?>
            <div class="alert alert-secondary">
                <?php echo e(__('home.yourAdvertIsDraft')); ?>

            </div>
        <?php endif; ?>
        <?php if(request('advert')->isDisabled()): ?>
            <div class="alert alert-secondary">
                <?php echo e(__('home.yourAdvertIsDisabled')); ?>

            </div>
        <?php endif; ?>
        <?php if(request('advert')->isWait()): ?>
            <div class="alert alert-secondary">
                <?php echo e(__('home.yourAdvertIsWait')); ?>

            </div>
        <?php endif; ?>
        <?php if(request('advert')->isModeration()): ?>
            <div class="alert alert-warning">
                <?php echo e(__('home.yourAdvertIsModeration')); ?>

            </div>
        <?php endif; ?>
    <?php endif; ?>

    <div class="mb-5">
        <ul class="nav nav-tabs bg-dark">
            <li class="nav-item">
                <?php echo e(Html::link(route('cabinet.advert.update', request('advert')), __('home.editAdvertInfo'), [
                    'class' => 'nav-link ' . (!Request::routeIs('cabinet.advert.update') ?: 'active')
                ])); ?>

            </li>
            <li class="nav-item">
                <?php echo e(Html::link(route('cabinet.advert.update.prices', request('advert')), __('home.editAdvertPrices'), [
                    'class' => 'nav-link ' . (!Request::routeIs('cabinet.advert.update.prices') ?: 'active')
                ])); ?>

            </li>
            <li class="nav-item">
                <?php echo e(Html::link(route('cabinet.advert.update.files', request('advert')), __('home.editAdvertFiles'), [
                    'class' => 'nav-link ' . (!Request::routeIs('cabinet.advert.update.files') ?: 'active')
                ])); ?>

            </li>
            <li class="nav-item">
                <?php echo e(Html::link(route('cabinet.advert.update.attribute', request('advert')), __('home.editAdvertAttribute'), [
                    'class' => 'nav-link ' . (!Request::routeIs('cabinet.advert.update.attribute') ?: 'active')
                ])); ?>

            </li>
            <?php if(request('advert') && request('advert')->accessSendToModeration()): ?>
                <li class="nav-item">
                    <?php echo e(Html::link(route('cabinet.advert.moderation', request('advert')), __('home.sendToModerationAdvert'), [
                        'class' => 'text-success nav-link ' . (!Request::routeIs('cabinet.advert.moderation') ?: 'active'),
                        'onclick' => 'return confirm("' . __('home.AreYouSure') . '");'
                    ])); ?>

                </li>
            <?php endif; ?>
        </ul>
    </div>

<?php $__env->stopSection(); ?>
<?php echo $__env->make('layouts.cabinet', array_except(get_defined_vars(), array('__data', '__path')))->render(); ?>