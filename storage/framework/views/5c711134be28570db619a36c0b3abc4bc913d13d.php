

<?php $__env->startSection('title', '| Tutor list'); ?>

<?php $__env->startSection('content'); ?>
    <h1 class="page-header">
        Профили репетиторов
    </h1>

    <hr />

    <?php echo e(Form::open([
        'method' => 'get',
        'url' => route('cabinet.admin.tutor.index')
    ])); ?>

        <div class="row">
            <div class="col-md-3">
                <div class="form-group">
                    <?php echo e(Form::label('id', '#')); ?>

                    <?php echo e(Form::text('id', request('id'), ['class' => 'form-control'])); ?>

                </div>
            </div>
            <div class="col-md-3">
                <div class="form-group">
                    <?php echo e(Form::label('user_id', 'User')); ?>

                    <?php echo e(Form::text('user_id', request('user_id'), ['class' => 'form-control'])); ?>

                </div>
            </div>
            <div class="col-md-3">
                <div class="form-group">
                    <?php echo e(Form::label('country_code', 'Country code')); ?>

                    <?php echo e(Form::text('country_code', request('country_code'), ['class' => 'form-control'])); ?>

                </div>
            </div>
            <div class="col-md-3">
                <div class="form-group">
                    <?php echo e(Form::label('phone', 'Phone')); ?>

                    <?php echo e(Form::text('phone', request('phone'), ['class' => 'form-control'])); ?>

                </div>
            </div>
            <div class="col-md-3">
                <div class="form-group">
                    <?php echo e(Form::label('phone_verified', 'Phone verified')); ?>

                    <?php echo e(Form::select('phone_verified', [
                        '' => 'Select',
                        1 => 'Yes',
                        0 => 'No'
                    ], request('phone_verified'), ['class' => 'form-control'])); ?>

                </div>
            </div>
            <div class="col-md-3">
                <div class="form-group">
                    <?php echo e(Form::label('status', 'Status')); ?>

                    <?php echo e(Form::select('status', ['' => 'Select'] + $model->statuses(), request('status'), ['class' => 'form-control'])); ?>

                </div>
            </div>
        </div>
        <?php echo e(Form::submit('Найти', ['class' => 'btn btn-success'])); ?>

        <a href="<?php echo e(route('cabinet.admin.tutor.index')); ?>" class="btn btn-warning">
            Сбросить
        </a>
    <?php echo e(Form::close()); ?>


    <hr />

    <div class="table-responsive">
        <table class="table table-bordered table-striped">
            <thead>
                <tr>
                    <th><?php echo $sort->link('id'); ?></th>
                    <th>User</th>
                    <th>Status</th>
                    <th>Phone</th>
                    <th>Управление</th>
                </tr>
            </thead>
            <tbody>
                <?php $__currentLoopData = $models; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $item): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                    <tr>
                        <td><?php echo e($item->id); ?></td>
                        <td>#<?php echo e($item->user->id); ?> - <?php echo e($item->user->name); ?></td>
                        <td><?php echo e($item->statuses()[$item->status]); ?></td>
                        <td>
                            <?php echo e($item->country_code); ?> <?php echo e($item->phone); ?>

                            <?php echo e($item->phone_verified ? 'Проверен' : ''); ?>

                        </td>
                        <td width="200">
                            <a href="<?php echo e(route('cabinet.admin.tutor.edit', $item->id)); ?>" class="btn btn-sm btn-info pull-left" style="margin-right: 3px;">
                                Edit
                            </a>

                            <?php echo Form::open([
                                'method' => 'DELETE',
                                'route' => ['cabinet.admin.tutor.destroy', $item->id],
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

<?php $__env->stopSection(); ?>
<?php echo $__env->make('layouts.admin', array_except(get_defined_vars(), array('__data', '__path')))->render(); ?>