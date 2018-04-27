<?php $__env->startSection('content'); ?>
    <h1 class="page-header">
        #<?php echo e($advert->id); ?> <?php echo e($advert->title); ?> - <?php echo e(__('home.errorSendToModerationAdvert')); ?>

    </h1>

    <hr />

    ##parent-placeholder-040f06fd774092478d450774f5ba30c5da78acc8##

    <div class="alert alert-danger">
        <?php echo e(__('home.alertDangerFixesErrorSendToModerationAdvert')); ?>

    </div>
<?php $__env->stopSection(); ?>
<?php echo $__env->make('layouts.advert-edit', array_except(get_defined_vars(), array('__data', '__path')))->render(); ?>