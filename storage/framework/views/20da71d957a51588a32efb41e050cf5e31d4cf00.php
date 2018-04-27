<?php $__env->startSection('title', '| Edit Category'); ?>

<?php $__env->startSection('content'); ?>
    <div>
        <h1>Редактировать категорию <?php echo e($category->name); ?></h1>

        <hr>

        <?php echo e(Form::open([
            'method' => 'PUT',
            'url' => route('cabinet.admin.category.update', $category)
        ])); ?>


        <?php if($parent): ?>
            <h4>Родительская категория</h4>
            <div class="mb-4">
                <?php echo $__env->make('cabinet.admin.category._face', ['category' => $parent], array_except(get_defined_vars(), array('__data', '__path')))->render(); ?>
            </div>
            <h4>Заполните поля для дочерней категории</h4>
            <hr />
        <?php endif; ?>

        <div class="form-group">
            <?php echo e(Form::label('parent_id', 'Родительская категория')); ?>

            <select name="parent_id" class="form-control">
                <option value="">Выбрать</option>
                <?php $__currentLoopData = $list; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $item): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                    <option value="<?php echo e($item->id); ?>" <?php echo e($item->id === $category->parent_id ? 'selected' : ''); ?>>
                        <?php echo $item->depth ? str_repeat('&mdash; ', $item->depth) : null; ?>

                        <?php echo e($item->name); ?>

                    </option>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
            </select>
            <?php if($errors->has('parent_id')): ?>
                <span class="invalid-feedback">
                        <strong><?php echo e($errors->first('parent_id')); ?></strong>
                    </span>
            <?php endif; ?>
        </div>

        <div class="form-group">
            <?php echo e(Form::label('name', 'Название')); ?>

            <?php echo e(Form::text('name', old('name', $category->name), ['class' => 'form-control ' . (!$errors->has('name') ?: 'is-invalid')])); ?>

            <?php if($errors->has('name')): ?>
                <span class="invalid-feedback">
                    <strong><?php echo e($errors->first('name')); ?></strong>
                </span>
            <?php endif; ?>
        </div>

        <div class="form-group">
            <?php echo e(Form::label('slug', 'Алиас (урл)')); ?>

            <?php echo e(Form::text('slug', old('slug', $category->slug), ['class' => 'form-control ' . (!$errors->has('slug') ?: 'is-invalid')])); ?>

            <?php if($errors->has('slug')): ?>
                <span class="invalid-feedback">
                    <strong><?php echo e($errors->first('slug')); ?></strong>
                </span>
            <?php endif; ?>
        </div>

        <div class="form-group">
            <?php echo e(Form::label('title', 'Тайтл')); ?>

            <?php echo e(Form::text('title', old('title', $category->title), ['class' => 'form-control ' . (!$errors->has('title') ?: 'is-invalid')])); ?>

            <?php if($errors->has('title')): ?>
                <span class="invalid-feedback">
                    <strong><?php echo e($errors->first('title')); ?></strong>
                </span>
            <?php endif; ?>
        </div>

        <div class="form-group">
            <?php echo e(Form::label('description', 'Описание')); ?>

            <?php echo e(Form::textarea('description', old('description', $category->description), ['class' => 'form-control ' . (!$errors->has('description') ?: 'is-invalid')])); ?>

            <?php if($errors->has('description')): ?>
                <span class="invalid-feedback">
                    <strong><?php echo e($errors->first('description')); ?></strong>
                </span>
            <?php endif; ?>
        </div>

        <?php echo e(Form::submit('Обновить', ['class' => 'btn btn-primary'])); ?>


        <?php echo e(Form::close()); ?>

    </div>
<?php $__env->stopSection(); ?>
<?php echo $__env->make('layouts.admin', array_except(get_defined_vars(), array('__data', '__path')))->render(); ?>