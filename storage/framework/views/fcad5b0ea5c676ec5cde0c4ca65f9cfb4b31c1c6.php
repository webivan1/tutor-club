<?php $__env->startSection('script.head'); ?>
    <script src="//cdn.ckeditor.com/4.8.0/basic/ckeditor.js"></script>
<?php $__env->stopSection(); ?>

<?php $__env->startSection('content'); ?>
    <h1 class="page-header">
        #<?php echo e($advert->id); ?> <?php echo e($advert->title); ?>

    </h1>

    <hr />

    ##parent-placeholder-040f06fd774092478d450774f5ba30c5da78acc8##

    <div>
        <?php echo e(Form::open(['method' => 'PUT', 'url' => route('cabinet.advert.update', $advert)])); ?>


            <?php echo $__env->make('advert.partials.info-fields', compact('advert'), array_except(get_defined_vars(), array('__data', '__path')))->render(); ?>

            <hr />

            <?php echo e(Form::submit('Сохранить', ['class' => 'btn btn-raised btn-success'])); ?>

        <?php echo e(Form::close()); ?>

    </div>
<?php $__env->stopSection(); ?>
<?php echo $__env->make('layouts.advert-edit', array_except(get_defined_vars(), array('__data', '__path')))->render(); ?>