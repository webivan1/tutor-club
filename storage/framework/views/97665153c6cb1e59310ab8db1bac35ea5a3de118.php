<?php $__env->startSection('content'); ?>
    <h1 class="page-header">
        #<?php echo e($advert->id); ?> <?php echo e($advert->title); ?> - <?php echo e(__('home.advertPricesHeading')); ?>

    </h1>

    <hr />

    ##parent-placeholder-040f06fd774092478d450774f5ba30c5da78acc8##

    <div>
        <?php echo e(Form::open(['method' => 'PUT', 'url' => route('cabinet.advert.update.prices', $advert)])); ?>


            <?php echo $__env->make('advert.partials.prices', [
                'advertPrice' => \App\Helpers\ArrayHelper::multipleDataFormToCorrectArray(old('prices')) ?? $prices,
                'types' => $types,
                'listCategory' => $listCategory
            ], array_except(get_defined_vars(), array('__data', '__path')))->render(); ?>

            <hr />

            <?php echo e(Form::submit('Сохранить', ['class' => 'btn btn-raised btn-success'])); ?>

        <?php echo e(Form::close()); ?>

    </div>
<?php $__env->stopSection(); ?>
<?php echo $__env->make('layouts.advert-edit', array_except(get_defined_vars(), array('__data', '__path')))->render(); ?>