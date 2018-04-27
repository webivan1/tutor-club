

<?php $__env->startSection('nav-left'); ?>
    <?php echo e(Html::link(route('profile.home'), 'Личный профиль', [
        'class' => 'list-group-item'
    ])); ?>

    <?php echo e(Html::link(route('profile.tutor.home'), 'Стать репетитором', [
        'class' => 'list-group-item text-warning'
    ])); ?>

<?php $__env->stopSection(); ?>

<?php $__env->startSection('content'); ?>
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-8">
                <div class="card">
                    <div class="card-header">Спасибо за регистрацию!</div>
                    <div class="card-body">
                        <div>Добро пожаловать в кабинет</div>
                    </div>
                </div>
            </div>
        </div>
    </div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.cabinet', array_except(get_defined_vars(), array('__data', '__path')))->render(); ?>