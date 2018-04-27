<div class="row mb-4">
    <div class="col-md-7">
        <div class="card">
            <div class="card-body">
                <div class="form-group">
                    <?php echo e(Form::label('lang', 'Укажите языковую пренадлежность объялению')); ?>

                    <?php echo e(Form::select('lang', \App\Helpers\LangHelper::langList(), old('lang', $advert->lang ?? app()->getLocale()), [
                        'class' => 'form-control ' . (!$errors->has('lang') ? '' : 'is-invalid')
                    ])); ?>

                    <?php if($errors->has('lang')): ?>
                        <div class="invalid-feedback">
                            <b><?php echo e($errors->first('lang')); ?></b>
                        </div>
                    <?php endif; ?>
                </div>
                <div class="form-group">
                    <?php echo e(Form::label('experience', 'Опыт преподавания в данной области (лет)')); ?>

                    <?php echo e(Form::input('number', 'experience', old('experience', $advert->experience ?? 0), [
                        'class' => 'form-control ' . (!$errors->has('experience') ? '' : 'is-invalid')
                    ])); ?>

                    <?php if($errors->has('experience')): ?>
                        <div class="invalid-feedback">
                            <b><?php echo e($errors->first('experience')); ?></b>
                        </div>
                    <?php endif; ?>
                </div>
                <div class="form-group">
                    <?php echo e(Form::label('presentation', 'Видео презентация (youtube url)')); ?>

                    <?php echo e(Form::text('presentation', old('presentation', $advert->presentation ?? ''), [
                        'class' => 'form-control ' . (!$errors->has('presentation') ? '' : 'is-invalid')
                    ])); ?>

                    <?php if($errors->has('presentation')): ?>
                        <div class="invalid-feedback">
                            <b><?php echo e($errors->first('presentation')); ?></b>
                        </div>
                    <?php endif; ?>
                </div>
                <div class="form-group">
                    <?php echo e(Form::label('description-cke', 'Опишите свой опыт и достижения')); ?>

                    <?php echo e(Form::textarea('description', old('description', $advert->description ?? ''), [
                        'class' => 'form-control',
                        'id' => 'description-cke'
                    ])); ?>

                    <?php if($errors->has('description')): ?>
                        <small class="text-danger">
                            <b><?php echo e($errors->first('description')); ?></b>
                        </small>
                    <?php endif; ?>
                    <script type="text/javascript">
                      //CKEDITOR.config.allowedContent = true;
                      CKEDITOR.replace('description-cke');
                    </script>
                </div>
            </div>
        </div>
    </div>
</div>