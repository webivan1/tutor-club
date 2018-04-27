

<?php $__env->startSection('title', '| Users'); ?>

<?php $__env->startSection('content'); ?>
    <h1>Список пользователей</h1>

    <?php echo e(Form::open([
        'method' => 'get',
        'url' => route('cabinet.admin.users.index')
    ])); ?>

        <div class="row">
            <div class="col-md-3">
                <div class="form-group">
                    <?php echo e(Form::label('id', 'Номер')); ?>

                    <?php echo e(Form::text('id', request('id'), ['class' => 'form-control'])); ?>

                </div>
            </div>
            <div class="col-md-3">
                <div class="form-group">
                    <?php echo e(Form::label('name', 'Имя')); ?>

                    <?php echo e(Form::text('name', request('name'), ['class' => 'form-control'])); ?>

                </div>
            </div>
            <div class="col-md-3">
                <div class="form-group">
                    <?php echo e(Form::label('email', 'Email')); ?>

                    <?php echo e(Form::text('email', request('email'), ['class' => 'form-control'])); ?>

                </div>
            </div>
            <div class="col-md-3">
                <div class="form-group">
                    <?php echo e(Form::label('status', 'Статус')); ?>

                    <?php echo e(Form::select('status', ['' => 'Выбрать'] + $statuses, request('status'), [
                        'class' => 'form-control'
                    ])); ?>

                </div>
            </div>
        </div>
        <div class="row">
            <div class="col-md-3">
                <div class="form-group">
                    <?php echo e(Form::label('role', 'Роль')); ?>

                    <?php echo e(Form::select('role', ['' => 'Выбрать'] + $roles, request('role'), [
                        'class' => 'form-control'
                    ])); ?>

                </div>
            </div>
        </div>
        <?php echo e(Form::submit('Найти', ['class' => 'btn btn-success'])); ?>

        <a href="<?php echo e(route('cabinet.admin.users.index')); ?>" class="btn btn-warning">
            Сбросить
        </a>
    <?php echo e(Form::close()); ?>


    <hr>

    <p>Всего пользователей: <?php echo e($models->total()); ?></p>

    <div class="table-responsive">
        <table class="table table-bordered table-striped">
            <thead>
            <tr>
                <th>
                    <?php echo $sort->link('id'); ?>

                </th>
                <th>
                    <?php echo $sort->link('name'); ?>

                </th>
                <th>Email</th>
                <th>Статус</th>
                <th>Роли</th>
                <th>
                    <?php echo $sort->link('created_at'); ?>

                </th>
                <th>
                    <?php echo $sort->link('updated_at'); ?>

                </th>
                <th>Управление</th>
            </tr>
            </thead>
            <tbody>
                <?php $__currentLoopData = $models; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $user): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                    <tr>
                        <td><?php echo e($user->id); ?></td>
                        <td><?php echo e($user->name); ?></td>
                        <td><?php echo e($user->email); ?></td>
                        <td>
                            <span class="badge badge-<?php echo e($user->statusesColor()[$user->status]); ?>">
                                <?php echo e($user->statusesLabels()[$user->status]); ?>

                            </span>
                        </td>
                        <td><?php echo e($user->roles()->pluck('name')->implode(' ')); ?></td>
                        <td><?php echo e($user->created_at->format('d.m.Y H:i')); ?></td>
                        <td><?php echo e($user->updated_at->format('d.m.Y H:i')); ?></td>
                        <td width="200">
                            <a href="<?php echo e(route('cabinet.admin.users.edit', $user->id)); ?>" class="btn btn-sm btn-info pull-left" style="margin-right: 3px;">
                                Edit
                            </a>

                            <?php echo Form::open([
                                'method' => 'DELETE',
                                'route' => ['cabinet.admin.users.destroy', $user->id],
                                'onsubmit' => 'return confirm("Вы уверены?");'
                            ]); ?>

                            <?php echo Form::submit('Delete', ['class' => 'btn btn-sm btn-danger']); ?>

                            <?php echo Form::close(); ?>

                        </td>
                    </tr>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
            </tbody>

        </table>
    </div>

    <?php echo e($models->links()); ?>


    <a href="<?php echo e(route('cabinet.admin.users.create')); ?>" class="btn btn-success">
        Добавить пользователя
    </a>
<?php $__env->stopSection(); ?>
<?php echo $__env->make('layouts.admin', array_except(get_defined_vars(), array('__data', '__path')))->render(); ?>