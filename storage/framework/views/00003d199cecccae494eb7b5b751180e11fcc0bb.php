<?php

use App\Entity\Advert\AdvertPrice;
use App\Helpers\ArrayHelper;

$advertPrice = ArrayHelper::multipleDataFormToCorrectArray(old('prices', []));
$advertPrice = empty($advertPrice) ? [new AdvertPrice()] : $advertPrice;

?>



<?php $__env->startSection('not-drawers', true); ?>

<?php $__env->startSection('script.head'); ?>
    <script src="//cdn.ckeditor.com/4.8.0/basic/ckeditor.js"></script>
<?php $__env->stopSection(); ?>

<?php $__env->startSection('content'); ?>
    <h1 class="page-header">
        <?php echo e($category->name); ?>

    </h1>

    <hr />

    <div>
        <?php echo e(Form::open(['method' => 'POST', 'url' => route('cabinet.advert.create.end', $category)])); ?>


            <?php echo $__env->make('advert.partials.info-fields', array_except(get_defined_vars(), array('__data', '__path')))->render(); ?>
            <?php echo $__env->make('advert.partials.prices', compact('advertPrice', 'listCategory', 'types'), array_except(get_defined_vars(), array('__data', '__path')))->render(); ?>

            <hr />

            <?php echo e(Form::submit('Сохранить и продолжить', ['class' => 'btn btn-raised btn-success'])); ?>

        <?php echo e(Form::close()); ?>

    </div>
<?php $__env->stopSection(); ?>
<?php echo $__env->make('layouts.cabinet', array_except(get_defined_vars(), array('__data', '__path')))->render(); ?>