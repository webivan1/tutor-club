<?php $__env->startSection('content'); ?>
    ##parent-placeholder-040f06fd774092478d450774f5ba30c5da78acc8##

    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-8">
                <div class="card">
                    <div class="card-body">
                        <?php echo e(Form::open(['method' => 'POST', 'url' => route('profile.password.form')])); ?>

                            <div class="form-group">
                                <?php echo e(Form::label('password', t('home.WriteNewPassword'), ['class' => 'bmd-label-floating'])); ?>

                                <?php echo e(Form::input('password', 'password', '', [
                                    'class' => 'form-control ' . (!$errors->has('password') ?: 'is-invalid')
                                ])); ?>

                                <?php if($errors->has('password')): ?>
                                    <span class="invalid-feedback">
                                    <strong><?php echo e($errors->first('password')); ?></strong>
                                </span>
                                <?php endif; ?>
                            </div>

                            <div class="form-group">
                                <?php echo e(Form::label('password_confirmation', t('home.ReplayNewPassword'), ['class' => 'bmd-label-floating'])); ?>

                                <?php echo e(Form::input('password', 'password_confirmation', '', [
                                    'class' => 'form-control'
                                ])); ?>

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