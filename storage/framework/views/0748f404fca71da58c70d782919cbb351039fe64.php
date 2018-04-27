<?php $__env->startSection('title', 'Добавить объявление'); ?>
<?php $__env->startSection('not-drawers', true); ?>

<?php $__env->startSection('nav-left'); ?>
    <?php echo e(Html::link(route('cabinet.advert.index'), t('home.listOwnAdverts'), ['class' => 'list-group-item'])); ?>

<?php $__env->stopSection(); ?>

<?php $__env->startSection('content'); ?>
    <h1 class="page-header">
        Выберите категорию
    </h1>

    <hr />

    <div class="list-group">
        <?php $__currentLoopData = $category; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $item): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
            <a class="list-group-item" href="<?php echo e(route('cabinet.advert.create.end', $item)); ?>">
                <?php echo e($item->name); ?>

            </a>
        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
    </div>
<?php $__env->stopSection(); ?>
<?php echo $__env->make('layouts.cabinet', array_except(get_defined_vars(), array('__data', '__path')))->render(); ?>