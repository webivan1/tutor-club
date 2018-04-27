

<?php $__env->startSection('title', '| Edit translate'); ?>

<?php $__env->startSection('content'); ?>
    <div>
        <h1>Редактировать перевод #<?php echo e($translate->name); ?></h1>

        <hr>

        <?php echo e(Form::open([
            'method' => 'PUT',
            'url' => route('cabinet.admin.translate.update', $translate)
        ])); ?>


        <div class="form-group">
            <?php echo e(Form::label('name', 'Ключ')); ?>

            <?php echo e(Form::text('name', old('name', $translate->name), ['class' => 'form-control ' . (!$errors->has('name') ?: 'is-invalid')])); ?>

            <?php if($errors->has('name')): ?>
                <span class="invalid-feedback">
                    <strong><?php echo e($errors->first('name')); ?></strong>
                </span>
            <?php endif; ?>
        </div>

        <?php $__currentLoopData = $langs; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $keyCode => $language): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
            <div class="form-group">
                <?php echo e(Form::label("translate[{$keyCode}]", $language['name'])); ?>

                <?php echo e(Form::textarea("translate[{$keyCode}]", old("translate[{$keyCode}]", empty($translated[$keyCode]) ?: $translated[$keyCode]), ['class' => 'form-control ' . (!$errors->has("translate[{$keyCode}]") ?: 'is-invalid')])); ?>

                <?php if($errors->has("translate[{$keyCode}]")): ?>
                    <span class="invalid-feedback">
                        <strong><?php echo e($errors->first("translate[{$keyCode}]")); ?></strong>
                    </span>
                <?php endif; ?>
            </div>
        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>

        <?php echo e(Form::submit('Обновить', ['class' => 'btn btn-primary'])); ?>


        <?php echo e(Form::close()); ?>

    </div>
<?php $__env->stopSection(); ?>
<?php echo $__env->make('layouts.admin', array_except(get_defined_vars(), array('__data', '__path')))->render(); ?>