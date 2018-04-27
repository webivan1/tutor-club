

<?php $__env->startSection('title', '| Adverts'); ?>

<?php $__env->startSection('content'); ?>
    <h1 class="page-header">
        Объявления репетиторов
    </h1>

    <hr />

    <?php echo e(Form::open([
        'method' => 'get',
        'url' => route('cabinet.admin.advert.index')
    ])); ?>

        <div class="row">
            <div class="col-md-3">
                <div class="form-group">
                    <?php echo e(Form::label('id', 'Номер')); ?>

                    <?php echo e(Form::text('id', request('id'), ['class' => 'form-control'])); ?>

                </div>
            </div>
            <div class="col-md-3">
                <div class="form-group">
                    <?php echo e(Form::label('profile_id', 'Профиль')); ?>

                    <?php echo e(Form::text('profile_id', request('profile_id'), ['class' => 'form-control'])); ?>

                </div>
            </div>
            <div class="col-md-3">
                <div class="form-group">
                    <?php echo e(Form::label('user_id', 'Юзер')); ?>

                    <?php echo e(Form::text('user_id', request('user_id'), ['class' => 'form-control'])); ?>

                </div>
            </div>
            <div class="col-md-3">
                <div class="form-group">
                    <?php echo e(Form::label('lang', 'Язык')); ?>

                    <?php echo e(Form::select('lang', \App\Helpers\LangHelper::langList(), request('lang'), ['class' => 'form-control'])); ?>

                </div>
            </div>
        </div>

        <div class="row">
            <div class="col-md-3">
                <div class="form-group">
                    <?php echo e(Form::label('price_from', 'Цена от')); ?>

                    <?php echo e(Form::text('price_from', request('price_from'), ['class' => 'form-control'])); ?>

                </div>
            </div>
            <div class="col-md-3">
                <div class="form-group">
                    <?php echo e(Form::label('price_type', 'Валюта')); ?>

                    <?php echo e(Form::select('price_type', ['' => 'Выбрать'] + \App\Entity\Advert\AdvertPrice::types(), request('price_type'), ['class' => 'form-control'])); ?>

                </div>
            </div>
        </div>

        <?php if($category): ?>
            <div class="row">
                <div class="col-md-6">
                    <div class="form-group">
                        <?php echo e(Form::label('category', 'Категории')); ?>

                        <select name="category[]" id="category" class="form-control" multiple>
                            <option value="">Выбрать</option>
                            <?php $__currentLoopData = $category; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $item): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                <option value="<?php echo e($item->id); ?>" <?php echo e(in_array($item->id, old('category', [])) ? 'selected' : null); ?>>
                                    <?php echo $item->depth > 0 ? str_repeat("&nbsp;&nbsp;", $item->depth) : null; ?>

                                    <?php echo e($item->name); ?>

                                </option>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                        </select>
                    </div>
                </div>
            </div>
        <?php endif; ?>

        <?php echo e(Form::submit('Найти', ['class' => 'btn btn-success'])); ?>

        <a href="<?php echo e(route('cabinet.admin.advert.index')); ?>" class="btn btn-warning">
            Сбросить
        </a>
    <?php echo e(Form::close()); ?>


    <hr />

    <div class="table-responsive">
        <table class="table table-bordered table-striped">
            <thead>
                <tr>
                    <th><?php echo $sort->link('id'); ?></th>
                    <th>Пользователь</th>
                    <th>Профиль</th>
                    <th>Название главной категории</th>
                    <th>Язык</th>
                    <th>Статус</th>
                    <th>Дата добавления</th>
                    <th>Дата обновления</th>
                    <th>Управление</th>
                </tr>
            </thead>
            <tbody>
                <?php $__currentLoopData = $models; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $item): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                    <tr>
                        <td><?php echo e($item->id); ?></td>
                        <td>
                            <?php echo e(Html::link(route('cabinet.admin.users.edit', $item->user_id), $item->user->name)); ?>

                        </td>
                        <td>
                            <?php echo e(Html::link(route('cabinet.admin.tutor.edit', $item->profile_id), $item->profile_id)); ?>

                        </td>
                        <td><?php echo e($item->title); ?></td>
                        <td><?php echo e($item->lang); ?></td>
                        <td><?php echo e($item->statuses()[$item->status]); ?></td>
                        <td><?php echo e($item->created_at); ?></td>
                        <td><?php echo e($item->updated_at); ?></td>
                        <td width="200">
                            <a href="<?php echo e(route('cabinet.admin.advert.edit', $item->id)); ?>" class="btn btn-sm btn-info pull-left" style="margin-right: 3px;">
                                Edit
                            </a>

                            <?php echo Form::open([
                                'method' => 'DELETE',
                                'route' => ['cabinet.admin.advert.destroy', $item->id],
                                'onsubmit' => 'return confirm("Вы уверены?");'
                            ]); ?>

                            <?php echo Form::submit('Delete', ['class' => 'btn btn-sm btn-danger']); ?>

                            <?php echo Form::close(); ?>

                        </td>
                    </tr>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
            </tbody>

        </table>
    </div>

    <?php echo e($models->links()); ?>

<?php $__env->stopSection(); ?>
<?php echo $__env->make('layouts.admin', array_except(get_defined_vars(), array('__data', '__path')))->render(); ?>