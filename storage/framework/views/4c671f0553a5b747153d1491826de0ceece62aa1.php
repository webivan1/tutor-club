<?php $__env->startSection('title', '| Edit advert'); ?>

<?php $__env->startSection('content'); ?>
    <div>
        <h1>Редактировать <?php echo e($advert->id); ?></h1>

        <hr>

        <?php echo e(Form::open([
            'method' => 'PUT',
            'url' => route('cabinet.admin.advert.update', $advert)
        ])); ?>


            <div>
                <?php echo e(Html::link(route('cabinet.advert.update', $advert), 'Посмотреть объявление', [
                    'class' => 'btn btn-link text-danger',
                    'target' => '_blank'
                ])); ?>

            </div>

            <div class="form-group">
                <?php echo e(Form::label('status', 'Статус')); ?>

                <?php echo e(Form::select('status', $advert->statuses(), old('status', $advert->status), ['class' => 'form-control ' . (!$errors->has('status') ?: 'is-invalid')])); ?>

                <?php if($errors->has('status')): ?>
                    <span class="invalid-feedback">
                        <strong><?php echo e($errors->first('status')); ?></strong>
                    </span>
                <?php endif; ?>
            </div>

            <?php echo e(Form::submit('Обновить', ['class' => 'btn btn-primary'])); ?>


        <?php echo e(Form::close()); ?>

    </div>
<?php $__env->stopSection(); ?>
<?php echo $__env->make('layouts.admin', array_except(get_defined_vars(), array('__data', '__path')))->render(); ?>