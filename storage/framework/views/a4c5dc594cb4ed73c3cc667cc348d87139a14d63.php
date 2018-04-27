<?php $__env->startSection('content'); ?>

    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-6">
                <div class="card">
                    <div class="card-header"><?php echo e(t('home.Register')); ?></div>
                    <div class="card-body">
                        <?php echo e(Form::open(['method' => 'POST', 'url' => route('register'), 'novalidate' => true])); ?>

                            <div class="form-group">
                                <?php echo e(Form::label('name', t('home.Username'), ['class' => 'bmd-label-floating'])); ?>

                                <?php echo e(Form::text('name', old('name'), [
                                    'class' => 'form-control' . ($errors->has('name') ? ' is-invalid' : ''),
                                    'validate' => true
                                ])); ?>

                                <?php if($errors->has('name')): ?>
                                    <span class="invalid-feedback">
                                        <strong><?php echo e($errors->first('name')); ?></strong>
                                    </span>
                                <?php endif; ?>
                            </div>

                            <div class="form-group">
                                <?php echo e(Form::label('email', 'Email', ['class' => 'bmd-label-floating'])); ?>

                                <?php echo e(Form::input('email', 'email', old('email'), [
                                    'class' => 'form-control' . ($errors->has('email') ? ' is-invalid' : ''),
                                    'validate' => true
                                ])); ?>

                                <?php if($errors->has('email')): ?>
                                    <span class="invalid-feedback">
                                            <strong><?php echo e($errors->first('email')); ?></strong>
                                        </span>
                                <?php endif; ?>
                            </div>

                            <div class="form-group">
                                <?php echo e(Form::label('password', t('home.LabelPassword'), ['class' => 'bmd-label-floating'])); ?>

                                <?php echo e(Form::input('password', 'password', old('password'), [
                                    'class' => 'form-control' . ($errors->has('password') ? ' is-invalid' : ''),
                                    'validate' => true
                                ])); ?>

                                <?php if($errors->has('password')): ?>
                                    <span class="invalid-feedback">
                                        <strong><?php echo e($errors->first('password')); ?></strong>
                                    </span>
                                <?php endif; ?>
                            </div>

                            <div class="form-group">
                                <?php echo e(Form::label('password_confirmation', t('home.LabelPasswordConfirm'), ['class' => 'bmd-label-floating'])); ?>

                                <?php echo e(Form::input('password', 'password_confirmation', old('password_confirmation'), [
                                    'class' => 'form-control' . ($errors->has('password_confirmation') ? ' is-invalid' : ''),
                                    'validate' => true
                                ])); ?>

                            </div>

                            <div class="text-right">
                                <button type="submit" class="btn btn-raised btn-primary">
                                    <?php echo e(t('Register')); ?>

                                </button>
                            </div>
                        <?php echo e(Form::close()); ?>

                    </div>
                </div>
            </div>
        </div>
    </div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.app', array_except(get_defined_vars(), array('__data', '__path')))->render(); ?>