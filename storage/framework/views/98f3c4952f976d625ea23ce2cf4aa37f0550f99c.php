<div class="table-responsive">
    <table class="table table-bordered table-striped">
        <thead>
            <tr>
                <th>#</th>
                <th>Название</th>
                <th>Тип</th>
                <th>Варианты</th>
                <th>Сортировка</th>
                <th>Обязательное</th>
                <th>Управление</th>
            </tr>
        </thead>
        <tbody>
            <?php $__currentLoopData = $models; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $item): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                <tr>
                    <td><?php echo e($item->id); ?></td>
                    <td><?php echo e($item->label); ?></td>
                    <td><?php echo e($item->types()[$item->type]); ?></td>
                    <td><?php echo str_replace("\n", "<br />", $item->variants); ?></td>
                    <td><?php echo e($item->sort); ?></td>
                    <td><?php echo e($item->required ? 'Да' : 'Нет'); ?></td>
                    <td width="200">
                        <a target="_blank" href="<?php echo e(route('cabinet.admin.category.attribute.edit', [$item->category_id, $item])); ?>" class="btn btn-sm btn-info pull-left" style="margin-right: 3px;">
                            Edit
                        </a>

                        <?php echo Form::open([
                            'method' => 'DELETE',
                            'url' => route('cabinet.admin.category.attribute.destroy', [$item->category_id, $item]),
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