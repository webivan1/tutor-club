<?php $__env->startSection('title', '| Edit User'); ?>

<?php $__env->startSection('content'); ?>
    <div>
        <h1>Редактировать пользователя #<?php echo e($user->id); ?></h1>

        <hr>

        <?php echo e(Form::open([
            'method' => 'PUT',
            'url' => route('cabinet.admin.users.update', $user)
        ])); ?>


            <div class="form-group">
                <?php echo e(Form::label('name', 'Name')); ?>

                <?php echo e(Form::text('name', old('name', $user->name), ['class' => 'form-control'])); ?>

            </div>

            <div class="form-group">
                <?php echo e(Form::label('email', 'Email')); ?>

                <?php echo e(Form::email('email', old('email', $user->email), ['class' => 'form-control'])); ?>

            </div>

            <div class='form-group'>
                <?php echo e(Form::label('role', 'Роль')); ?>

                <?php echo e(Form::select('role', ['' => 'Выбрать'] + $roles, old('role', $user->roleUser ? $user->roleUser->role_id : ''), [
                    'class' => 'form-control'
                ])); ?>

            </div>

            <div class='form-group'>
                <?php echo e(Form::label('status', 'Статус')); ?>

                <?php echo e(Form::select('status', ['' => 'Выбрать'] + $statuses, old('status', $user->status), [
                    'class' => 'form-control'
                ])); ?>

            </div>

            <?php echo e(Form::submit('Обновить', ['class' => 'btn btn-primary'])); ?>


        <?php echo e(Form::close()); ?>

    </div>
<?php $__env->stopSection(); ?>
<?php echo $__env->make('layouts.admin', array_except(get_defined_vars(), array('__data', '__path')))->render(); ?>