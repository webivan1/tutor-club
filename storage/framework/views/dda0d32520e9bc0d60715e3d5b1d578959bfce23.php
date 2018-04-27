

<?php $__env->startSection('title', '| Role'); ?>

<?php $__env->startSection('content'); ?>
    <h1 class="page-header">
        Список ролей
    </h1>

    <hr />

    <?php echo e(Form::open([
        'method' => 'get',
        'url' => route('cabinet.admin.role.index')
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
                    <?php echo e(Form::label('title', 'Название роли')); ?>

                    <?php echo e(Form::text('title', request('title'), ['class' => 'form-control'])); ?>

                </div>
            </div>
            <div class="col-md-3">
                <div class="form-group">
                    <?php echo e(Form::label('name', 'Роль')); ?>

                    <?php echo e(Form::text('name', request('name'), ['class' => 'form-control'])); ?>

                </div>
            </div>
            <div class="col-md-3">
                <div class="form-group">
                    <?php echo e(Form::label('permission', 'Разрешение')); ?>

                    <?php echo e(Form::select('permission', $permissions, request('permission'), [
                        'class' => 'form-control'
                    ])); ?>

                </div>
            </div>
        </div>
        <?php echo e(Form::submit('Найти', ['class' => 'btn btn-success'])); ?>

        <a href="<?php echo e(route('cabinet.admin.role.index')); ?>" class="btn btn-warning">
            Сбросить
        </a>
    <?php echo e(Form::close()); ?>


    <hr />

    <div class="table-responsive">
        <table class="table table-bordered table-striped">
            <thead>
            <tr>
                <th>
                    <?php echo $sort->link('id'); ?>

                </th>
                <th>
                    <?php echo $sort->link('level'); ?>

                </th>
                <th>
                    <?php echo $sort->link('title'); ?>

                </th>
                <th>
                    <?php echo $sort->link('name'); ?>

                </th>
                <th>Управление</th>
            </tr>
            </thead>
            <tbody>
            <?php $__currentLoopData = $models; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $role): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                <tr>
                    <td><?php echo e($role->id); ?></td>
                    <td><?php echo e($role->level); ?></td>
                    <td><?php echo e($role->title); ?></td>
                    <td><?php echo e($role->name); ?></td>
                    <td width="200">
                        <a href="<?php echo e(route('cabinet.admin.role.edit', $role->id)); ?>" class="btn btn-sm btn-info pull-left" style="margin-right: 3px;">
                            Edit
                        </a>

                        <?php echo Form::open([
                            'method' => 'DELETE',
                            'route' => ['cabinet.admin.role.destroy', $role->id],
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


    <a href="<?php echo e(route('cabinet.admin.role.create')); ?>" class="btn btn-success">
        Добавить роль
    </a>
<?php $__env->stopSection(); ?>
<?php echo $__env->make('layouts.admin', array_except(get_defined_vars(), array('__data', '__path')))->render(); ?>