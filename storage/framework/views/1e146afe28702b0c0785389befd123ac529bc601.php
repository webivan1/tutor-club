<?php $__env->startSection('content'); ?>
    <h1 class="page-header">
        #<?php echo e($advert->id); ?> <?php echo e($advert->title); ?> - <?php echo e(__('home.editAdvertAttributesHeading')); ?>

    </h1>

    <hr />

    ##parent-placeholder-040f06fd774092478d450774f5ba30c5da78acc8##

    <?php if(!empty($attributes)): ?>
        <div class="row">
            <div class="col-md-7">
                <?php echo e(Form::open(['method' => 'PUT', 'url' => route('cabinet.advert.update.attribute', $advert)])); ?>


                    <div class="card">
                        <div class="card-body">
                            <?php $__currentLoopData = $attributes; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $attribute): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                <div class="form-group">
                                    <?php switch($attribute->type):
                                        case ($attribute->isStyleInlineField()): ?>
                                        <?php echo e(Form::label("attr-" . $attribute->id, __($attribute->label))); ?>

                                        <?php if($attribute->isNumber() || $attribute->isFloat()): ?>
                                            <?php echo e(Form::input('number', "attr[{$attribute->id}]", old("attr[{$attribute->id}]", $attribute->value), [
                                                'class' => 'form-control',
                                                'required' => $attribute->required
                                            ])); ?>

                                        <?php elseif($attribute->isText()): ?>
                                            <?php echo e(Form::text("attr[{$attribute->id}]", old("attr[{$attribute->id}]", $attribute->value), [
                                                'class' => 'form-control',
                                                'required' => $attribute->required
                                            ])); ?>

                                        <?php elseif($attribute->isSelect()): ?>
                                            <?php echo e(Form::select("attr[{$attribute->id}]", $attribute->variantsToArray(), old("attr[{$attribute->id}]", $attribute->value), [
                                                'class' => 'form-control',
                                                'required' => $attribute->required
                                            ])); ?>

                                        <?php endif; ?>
                                        <?php break; ?>
                                        <?php case ($attribute->isStyleCheckField()): ?>
                                        <?php if($attribute->isCheckbox()): ?>
                                            <div class="checkbox">
                                                <label>
                                                    <?php echo e(Form::checkbox("attr[{$attribute->id}]", 1, old("attr[{$attribute->id}]", !empty($attribute->value)) ? true : false)); ?>

                                                    <?php echo e(__($attribute->label)); ?>

                                                </label>
                                            </div>
                                        <?php elseif($attribute->isRadio()): ?>
                                            <?php $__currentLoopData = $attribute->variantsToArray(); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $key => $value): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                                <div class="radio">
                                                    <label>
                                                        <?php echo e(Form::radio("attr[{$attribute->id}]", $key, old("attr[{$attribute->id}]", $attribute->value) === $key ? true : false)); ?>

                                                        <?php echo e(__($value)); ?>

                                                    </label>
                                                </div>
                                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                        <?php endif; ?>
                                        <?php break; ?>
                                    <?php endswitch; ?>
                                </div>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                        </div>
                    </div>

                    <hr />

                    <?php echo e(Form::submit(__('home.Save'), ['class' => 'btn btn-raised btn-success'])); ?>

                <?php echo e(Form::close()); ?>

            </div>
        </div>
    <?php endif; ?>
<?php $__env->stopSection(); ?>
<?php echo $__env->make('layouts.advert-edit', array_except(get_defined_vars(), array('__data', '__path')))->render(); ?>