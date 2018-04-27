<?php

use App\Entity\TutorProfile;

/**
 * @var TutorProfile $model
 */

?>



<?php $__env->startSection('script.head'); ?>
    <script src="//cdn.ckeditor.com/4.9.1/basic/ckeditor.js"></script>
<?php $__env->stopSection(); ?>

<?php $__env->startSection('content'); ?>
    ##parent-placeholder-040f06fd774092478d450774f5ba30c5da78acc8##

    <div class="row">
        <?php echo e(Form::open([
            'method' => !$model->id ? 'POST' : 'PUT',
            'url' => route('profile.tutor.form'),
            'class' => 'col-md-7',
            'enctype' => 'multipart/form-data'
        ])); ?>


            <div class="card">
                <div class="card-body">
                    <div class="form-group">
                        <?php echo e(Form::label('phone', 'Телефон', ['class' => 'bmd-label-floating'])); ?>


                        <div class="row">
                            <div class="col-sm-4">
                                <?php echo e(Form::input('text', 'country_code', old('country_code', $model->country_code), [
                                    'class' => 'form-control ' . (!$errors->has('country_code') ?: ' is-invalid'),
                                    'placeholder' => ''
                                ])); ?>

                                <small class="text-muted">Код страны (+7)</small>
                            </div>
                            <div class="col-sm-8">
                                <?php echo e(Form::input('number', 'phone', old('phone', $model->phone), [
                                    'class' => 'form-control ' . (!$errors->has('phone') ?: ' is-invalid'),
                                    'placeholder' => ''
                                ])); ?>

                                <small class="text-muted">Номер телефона (9159998877)</small>
                            </div>
                        </div>

                        <?php if($errors->has('country_code') || $errors->has('phone')): ?>
                            <span class="text-danger">
                                <?php echo e(!$errors->has('country_code') ? '' : $errors->first('country_code')); ?>

                                <?php echo e(!$errors->has('phone') ? '' : $errors->first('phone')); ?>

                            </span>
                        <?php endif; ?>
                    </div>

                    <div class="form-group">
                        <?php echo e(Form::label('gender', 'Выберите пол', ['class' => 'bmd-label-floating'])); ?>

                        <?php echo e(Form::select('gender', ['' => 'Выбрать'] + $model->genders(), old('gender', $model->gender), [
                            'class' => 'form-control ' . (!$errors->has('gender') ?: ' is-invalid')
                        ])); ?>

                        <?php if($errors->has('gender')): ?>
                            <span class="invalid-feedback">
                                <strong><?php echo e($errors->first('gender')); ?></strong>
                            </span>
                        <?php endif; ?>
                    </div>

                    <div class="form-group">
                        <?php if($model->id && $model->image): ?>
                            <div class="mb-2">
                                <?php echo e(Html::image($model->image->file_path . '?t=' . time(), 'Аватар', [
                                    'class' => 'img-thumbnail'
                                ])); ?>

                            </div>
                        <?php endif; ?>

                        <?php echo e(Form::label('photo', 'Аватар', ['class' => 'bmd-label-floating'])); ?>

                        <?php echo e(Form::file('photo', [
                            'class' => 'form-control ' . (!$errors->has('photo') ?: ' is-invalid'),
                            'value' => !$model->image ?: $model->image->file_path
                        ])); ?>

                        <?php if($errors->has('photo')): ?>
                            <span class="invalid-feedback">
                                <strong><?php echo e($errors->first('photo')); ?></strong>
                            </span>
                        <?php endif; ?>
                    </div>

                </div>
            </div>

            <hr />

            <h3><?php echo e(t('home.formTutorProfileHeading')); ?></h3>
            <div class="help-block mb-4">
                <?php echo e(t('home.formTutorDescription')); ?>

            </div>

            <?php $__currentLoopData = LaravelLocalization::getSupportedLocales(); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $lang => $locale): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                <div class="card mb-3">
                    <div class="card-header">
                        <?php echo e($locale['native']); ?>

                    </div>
                    <div class="card-body">
                        <div class="form-group">
                            <?php echo e(Form::label("description[{$lang}]", t('home.smallDescription'))); ?>

                            <?php echo e(Form::textarea("description[{$lang}]", old("description[{$lang}]", $model->getText('description', $lang)), [
                                'class' => 'cke-editor form-control ' . (!$errors->has('description.' . $lang) ?: ' is-invalid'),
                            ])); ?>

                            <?php if($errors->has('description.' . $lang)): ?>
                                <span class="invalid-feedback">
                                    <strong><?php echo e($errors->first('description.' . $lang)); ?></strong>
                                </span>
                            <?php endif; ?>
                        </div>
                        <div class="form-group">
                            <?php echo e(Form::label('content-' . $lang, t('home.largeDescription'))); ?>

                            <?php echo e(Form::textarea("content[{$lang}]", old("content[{$lang}]", $model->getText('content', $lang)), [
                                'class' => 'form-control ' . (!$errors->has('content.' . $lang) ?: ' is-invalid'),
                                'id' => 'content-' . $lang
                            ])); ?>

                            <script type="text/javascript">
                                CKEDITOR.replace('<?php echo e('content-' . $lang); ?>');
                            </script>
                            <?php if($errors->has('content.' . $lang)): ?>
                                <span class="invalid-feedback">
                                    <strong><?php echo e($errors->first('content.' . $lang)); ?></strong>
                                </span>
                            <?php endif; ?>
                        </div>
                    </div>
                </div>
            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>

            <button type="submit" class="btn btn-raised btn-primary">
                <?php echo e(t('home.Save')); ?>

            </button>

        <?php echo e(Form::close()); ?>

    </div>
<?php $__env->stopSection(); ?>
<?php echo $__env->make('layouts.profile', array_except(get_defined_vars(), array('__data', '__path')))->render(); ?>