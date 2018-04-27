<?php $__env->startSection('title', '| Add Category'); ?>

<?php $__env->startSection('content'); ?>
    <div>
        <h1>Добавить категорию</h1>

        <hr>

        <?php echo e(Form::open([
            'method' => 'POST',
            'url' => route('cabinet.admin.category.store')
        ])); ?>


        <?php if($parent): ?>
            <h4>Родительская категория</h4>
            <div class="mb-4">
                <?php echo $__env->make('cabinet.admin.category._face', ['category' => $parent], array_except(get_defined_vars(), array('__data', '__path')))->render(); ?>
            </div>
            <h4>Заполните поля для дочерней категории</h4>
            <hr />

            <?php echo e(Form::hidden('parent_id', $parent->id)); ?>

        <?php endif; ?>

        <div class="form-group">
            <?php echo e(Form::label('name', 'Название')); ?>

            <?php echo e(Form::text('name', old('name'), ['class' => 'form-control ' . (!$errors->has('name') ?: 'is-invalid')])); ?>

            <?php if($errors->has('name')): ?>
                <span class="invalid-feedback">
                    <strong><?php echo e($errors->first('name')); ?></strong>
                </span>
            <?php endif; ?>
        </div>

        <div class="form-group">
            <?php echo e(Form::label('slug', 'Алиас (урл)')); ?>

            <?php echo e(Form::text('slug', old('slug'), ['class' => 'form-control ' . (!$errors->has('slug') ?: 'is-invalid')])); ?>

            <?php if($errors->has('slug')): ?>
                <span class="invalid-feedback">
                    <strong><?php echo e($errors->first('slug')); ?></strong>
                </span>
            <?php endif; ?>
        </div>

        <div class="form-group">
            <?php echo e(Form::label('title', 'Тайтл')); ?>

            <?php echo e(Form::text('title', old('title'), ['class' => 'form-control ' . (!$errors->has('title') ?: 'is-invalid')])); ?>

            <?php if($errors->has('title')): ?>
                <span class="invalid-feedback">
                    <strong><?php echo e($errors->first('title')); ?></strong>
                </span>
            <?php endif; ?>
        </div>

        <div class="form-group">
            <?php echo e(Form::label('description', 'Описание')); ?>

            <?php echo e(Form::textarea('description', old('description'), ['class' => 'form-control ' . (!$errors->has('description') ?: 'is-invalid')])); ?>

            <?php if($errors->has('description')): ?>
                <span class="invalid-feedback">
                    <strong><?php echo e($errors->first('description')); ?></strong>
                </span>
            <?php endif; ?>
        </div>

        <?php echo e(Form::submit('Добавить', ['class' => 'btn btn-primary'])); ?>


        <?php echo e(Form::close()); ?>

    </div>
<?php $__env->stopSection(); ?>
<?php echo $__env->make('layouts.admin', array_except(get_defined_vars(), array('__data', '__path')))->render(); ?>