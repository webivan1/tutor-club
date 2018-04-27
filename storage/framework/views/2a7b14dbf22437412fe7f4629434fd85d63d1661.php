<div class="alert alert-secondary">
    Выберите категории и заполните цены
</div>

<div class="clone-prices">
    <?php $__currentLoopData = $advertPrice; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $key => $value): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
        <div class="card mb-3 js-item">
            <div class="card-body">
                <div class="row">
                    <div class="col-md-3">
                        <div class="form-group">
                            <label>Выберите подкатегорию</label>
                            <select name="prices[category_id][]" class="form-control <?php echo e(!$errors->has('prices.category_id.' . $key) ? '' : 'is-invalid'); ?>">
                                <option value="">Выбрать</option>
                                <?php $__currentLoopData = $listCategory; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $item): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                    <option value="<?php echo e($item->id); ?>" <?php echo e(isset($value['category_id']) && $value['category_id'] == $item->id ? 'selected' : ''); ?>>
                                        <?php echo $item->depth > 1 ? str_repeat('&nbsp;&nbsp;', $item->depth) : ''; ?>

                                        <?php echo e($item->name); ?>

                                    </option>
                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                            </select>
                            <?php if($errors->has('prices.category_id.' . $key)): ?>
                                <div class="invalid-feedback">
                                    <b><?php echo e($errors->first('prices.category_id.' . $key)); ?></b>
                                </div>
                            <?php endif; ?>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="form-group">
                            <?php echo e(Form::label('price_type', 'Валюта')); ?>

                            <?php echo e(Form::select('prices[price_type][]', $types, $value['price_type'] ?? null, [
                                'class' => 'form-control ' . (!$errors->has('prices.price_type.' . $key) ? '' : 'is-invalid')
                            ])); ?>

                            <?php if($errors->has('prices.price_type.' . $key)): ?>
                                <div class="invalid-feedback">
                                    <b><?php echo e($errors->first('prices.price_type.' . $key)); ?></b>
                                </div>
                            <?php endif; ?>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="form-group">
                            <?php echo e(Form::label('price_from', 'Цена')); ?>

                            <?php echo e(Form::input('float', 'prices[price_from][]', $value['price_from'] ?? null, [
                                'class' => 'form-control ' . (!$errors->has('prices.price_from.' . $key) ? '' : 'is-invalid')
                            ])); ?>

                            <?php if($errors->has('prices.price_from.' . $key)): ?>
                                <div class="invalid-feedback">
                                    <b><?php echo e($errors->first('prices.price_from.' . $key)); ?></b>
                                </div>
                            <?php endif; ?>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="form-group">
                            <?php echo e(Form::label('price_from', 'Длительность 1 занятия (минут)')); ?>

                            <?php echo e(Form::input('float', 'prices[minutes][]', $value['minutes'] ?? null, [
                                'class' => 'form-control ' . (!$errors->has('prices.minutes.' . $key) ? '' : 'is-invalid')
                            ])); ?>

                            <?php if($errors->has('prices.minutes.' . $key)): ?>
                                <div class="invalid-feedback">
                                    <b><?php echo e($errors->first('prices.minutes.' . $key)); ?></b>
                                </div>
                            <?php endif; ?>
                        </div>
                    </div>
                </div>
                <div class="text-right">
                    <button
                        type="button"
                        onclick="deleteItem(this, '.clone-prices')"
                        class="btn btn-danger bmd-btn-fab bmd-btn-fab-sm"
                    >
                        <i class="material-icons">close</i>
                    </button>
                </div>
            </div>
        </div>
    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
</div>

<div class="text-right">
    <button type="button" class="btn btn-raised btn-info" data-clone-container=".clone-prices">
        Добавить ещё подкатегорию +
    </button>
</div>