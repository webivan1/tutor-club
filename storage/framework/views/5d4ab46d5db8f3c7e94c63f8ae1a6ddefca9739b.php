<?php $__env->startSection('breadcrumbs', ''); ?>

<?php $__env->startSection('content'); ?>
    <div class="card">
        <div class="card-body">
            <h1>
                Home page
            </h1>

            <div class="py-3">
                Hello!
            </div>
        </div>
    </div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.app', array_except(get_defined_vars(), array('__data', '__path')))->render(); ?>