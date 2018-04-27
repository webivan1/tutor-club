

<?php $__env->startSection('title', '| Languages key'); ?>

<?php $__env->startSection('content'); ?>
    <h1 class="page-header">
        <?php echo e(Html::link(route('cabinet.admin.translate.generate'), 'Сгенерировать переводчик', [
            'class' => 'btn btn-primary pull-right'
        ])); ?>

        Переводы
    </h1>

    <hr />

    <?php echo e(Form::open([
        'method' => 'get',
        'url' => route('cabinet.admin.translate.index')
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
                    <?php echo e(Form::label('name', 'Ключ')); ?>

                    <?php echo e(Form::text('name', request('name'), ['class' => 'form-control'])); ?>

                </div>
            </div>
            <div class="col-md-3">
                <div class="form-group">
                    <?php echo e(Form::label('translate', 'Часть текста')); ?>

                    <?php echo e(Form::text('translate', request('translate'), ['class' => 'form-control'])); ?>

                </div>
            </div>
            <div class="col-md-3">
                <div class="form-group">
                    <?php echo e(Form::label('language', 'Язык')); ?>

                    <?php echo e(Form::select('language',
                        ['' => 'Выбрать'] +
                        array_combine(
                            LaravelLocalization::getSupportedLanguagesKeys(),
                            LaravelLocalization::getSupportedLanguagesKeys()
                        ),
                        request('language'),
                        ['class' => 'form-control']
                    )); ?>

                </div>
            </div>
        </div>
        <?php echo e(Form::submit('Найти', ['class' => 'btn btn-success'])); ?>

        <a href="<?php echo e(route('cabinet.admin.translate.index')); ?>" class="btn btn-warning">
            Сбросить
        </a>
    <?php echo e(Form::close()); ?>


    <hr />

    <div class="table-responsive">
        <table class="table table-bordered table-striped">
            <thead>
                <tr>
                    <th>
                        <?php echo $sort->link('id'); ?>

                    </th>
                    <th>
                        <?php echo $sort->link('name'); ?>

                    </th>
                    <th>
                        Перевод
                    </th>
                    <th>Управление</th>
                </tr>
            </thead>
            <tbody>
                <?php $__currentLoopData = $models; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $item): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                    <tr>
                        <td><?php echo e($item->id); ?></td>
                        <td><?php echo e($item->name); ?></td>
                        <td>
                            <?php echo e(!$item->word ? null : $item->word->translate); ?>

                        </td>
                        <td width="200">
                            <a href="<?php echo e(route('cabinet.admin.translate.edit', $item)); ?>" class="btn btn-sm btn-info pull-left" style="margin-right: 3px;">
                                Edit
                            </a>

                            <?php echo Form::open([
                                'method' => 'DELETE',
                                'route' => ['cabinet.admin.translate.destroy', $item],
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


    <a href="<?php echo e(route('cabinet.admin.translate.create')); ?>" class="btn btn-success">
        Добавить перевод
    </a>
<?php $__env->stopSection(); ?>
<?php echo $__env->make('layouts.admin', array_except(get_defined_vars(), array('__data', '__path')))->render(); ?>