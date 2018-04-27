

<?php $__env->startSection('content'); ?>
    ##parent-placeholder-040f06fd774092478d450774f5ba30c5da78acc8##

    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-8">
                <div class="card">
                    <div class="card-header">
                        <?php echo e(Html::link(route('profile.edit.form'), t('home.EditProfile'), [
                            'class' => 'btn btn-primary mr-2'
                        ])); ?>

                        <?php echo e(Html::link(route('profile.password.form'), t('home.ChangePassword'), [
                            'class' => 'btn btn-primary mr-2'
                        ])); ?>

                        <?php echo e(Html::link(route('profile.email.form'), t('home.ChangeEmail'), [
                            'class' => 'btn btn-primary'
                        ])); ?>

                    </div>

                    <table class="table table-striped mb-0">
                        <tr>
                            <td>Ваш номер в системе</td>
                            <td><?php echo e($user->id); ?></td>
                        </tr>
                        <tr>
                            <td>Имя</td>
                            <td><?php echo e($user->name); ?></td>
                        </tr>
                        <tr>
                            <td>Email</td>
                            <td><?php echo e($user->email); ?></td>
                        </tr>
                        <tr>
                            <td>Дата регистрации</td>
                            <td><?php echo e(date('d.m.Y H:i', strtotime($user->created_at))); ?></td>
                        </tr>
                    </table>
                </div>
            </div>
        </div>
    </div>
<?php $__env->stopSection(); ?>
<?php echo $__env->make('layouts.profile', array_except(get_defined_vars(), array('__data', '__path')))->render(); ?>