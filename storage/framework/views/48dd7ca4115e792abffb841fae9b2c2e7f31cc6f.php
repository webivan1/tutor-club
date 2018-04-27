<?php $__env->startSection('content'); ?>
    ##parent-placeholder-040f06fd774092478d450774f5ba30c5da78acc8##

    <?php echo e(__('mail.hello', ['username' => $user->name])); ?>

    <?php echo e(__('mail.register_info', ['pass' => $user->getOriginPassword()])); ?>

    <?php if($user->isWait()): ?>
        <a href="<?php echo e(route('verify', ['token' => $user->verify_token])); ?>">
            <?php echo e(__('mail.activation_link')); ?>

        </a>
    <?php endif; ?>
<?php $__env->stopSection(); ?>
<?php echo $__env->make('layouts.email', array_except(get_defined_vars(), array('__data', '__path')))->render(); ?>