<?php $__env->startSection('nav-left'); ?>
    <?php echo e(Html::link(route('profile.home'), t('home.profileLink'), [
        'class' => 'list-group-item ' . (!Request::routeIs('profile.home') ?: 'active')
    ])); ?>

    <?php echo e(Html::link(route('profile.tutor.home'), t('home.profileTutorLink'), [
        'class' => 'list-group-item ' . (!Request::routeIs('profile.tutor.*') ?: 'active')
    ])); ?>

    <?php if(Auth::user()->can('access-advert')): ?>
        <?php echo e(Html::link(route('cabinet.advert.index'), t('home.myAdvert'), [
            'class' => 'list-group-item ' . (!Request::routeIs('cabinet.advert.*') ?: 'active')
        ])); ?>

    <?php endif; ?>
<?php $__env->stopSection(); ?>

<?php $__env->startSection('content'); ?>

<?php $__env->stopSection(); ?>
<?php echo $__env->make('layouts.cabinet', array_except(get_defined_vars(), array('__data', '__path')))->render(); ?>