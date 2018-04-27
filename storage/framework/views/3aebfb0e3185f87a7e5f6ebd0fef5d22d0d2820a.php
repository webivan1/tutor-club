<?php $__env->startSection('title', '| Category'); ?>

<?php $__env->startSection('content'); ?>
    <h1 class="page-header">
        Категории
    </h1>

    <?php echo $__env->make('cabinet.admin.category._list', compact('models'), array_except(get_defined_vars(), array('__data', '__path')))->render(); ?>

    <a href="<?php echo e(route('cabinet.admin.category.create')); ?>" class="btn btn-success">
        Добавить категорию
    </a>
<?php $__env->stopSection(); ?>
<?php echo $__env->make('layouts.admin', array_except(get_defined_vars(), array('__data', '__path')))->render(); ?>