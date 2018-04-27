<?php $__env->startSection('content'); ?>
    ##parent-placeholder-040f06fd774092478d450774f5ba30c5da78acc8##

    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-8">
                <div class="card">
                    <div class="card-body">
                        <?php echo e(Form::open(['method' => 'GET', 'url' => route('profile.email.send')])); ?>

                            <div class="form-group">
                                <?php echo e(Form::label('email', t('home.WriteNewEmail'), ['class' => 'bmd-label-floating'])); ?>

                                <?php echo e(Form::input('email', 'email', old('email'), [
                                    'class' => 'form-control ' . (!$errors->has('email') ?: 'is-invalid')
                                ])); ?>

                                <?php if($errors->has('email')): ?>
                                    <span class="invalid-feedback">
                                    <strong><?php echo e($errors->first('email')); ?></strong>
                                </span>
                                <?php endif; ?>
                            </div>

                            <?php echo e(Form::submit(t('home.Save'), ['class' => 'btn btn-raised btn-success'])); ?>

                        <?php echo e(Form::close()); ?>

                    </div>
                </div>
            </div>
        </div>
    </div>
<?php $__env->stopSection(); ?>
<?php echo $__env->make('layouts.profile', array_except(get_defined_vars(), array('__data', '__path')))->render(); ?>