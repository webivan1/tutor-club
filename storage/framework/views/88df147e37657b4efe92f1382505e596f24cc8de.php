<?php $__env->startSection('title', '| Edit tutor profile'); ?>

<?php $__env->startSection('content'); ?>
    <div>
        <h1>Редактировать #<?php echo e($tutor->id); ?></h1>

        <hr>

        
        <?php echo $__env->make('tutor._profile', ['profile' => $tutor], array_except(get_defined_vars(), array('__data', '__path')))->render(); ?>

        <?php echo e(Form::open([
            'method' => 'PUT',
            'url' => route('cabinet.admin.tutor.update', $tutor)
        ])); ?>


            <div class="form-group">
                <?php echo e(Form::label('status', 'Status')); ?>

                <?php echo e(Form::select('status', $tutor->statuses(), old('status', $tutor->status), ['class' => 'form-control ' . (!$errors->has('status') ?: 'is-invalid')])); ?>

                <?php if($errors->has('status')): ?>
                    <span class="invalid-feedback">
                        <strong><?php echo e($errors->first('status')); ?></strong>
                    </span>
                <?php endif; ?>
            </div>

            <div class="form-group">
                <?php echo e(Form::label('comment', 'Comment')); ?>

                <?php echo e(Form::textarea('comment', old('comment', $tutor->comment), ['class' => 'form-control ' . (!$errors->has('comment') ?: 'is-invalid')])); ?>

                <?php if($errors->has('comment')): ?>
                    <span class="invalid-feedback">
                        <strong><?php echo e($errors->first('comment')); ?></strong>
                    </span>
                <?php endif; ?>
            </div>

            <?php echo e(Form::submit('Обновить', ['class' => 'btn btn-primary'])); ?>


        <?php echo e(Form::close()); ?>

    </div>
<?php $__env->stopSection(); ?>
<?php echo $__env->make('layouts.admin', array_except(get_defined_vars(), array('__data', '__path')))->render(); ?>