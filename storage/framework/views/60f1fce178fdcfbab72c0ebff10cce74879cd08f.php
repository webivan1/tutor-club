<div class="table-responsive">
    <table class="table table-bordered table-striped">
        <thead>
            <tr>
                <th>#</th>
                <th>Название</th>
                <th>Алиас</th>
                <th>Вложенность</th>
                <th>Дочерних категорий</th>
                <th>Сортировка</th>
                <th>Управление</th>
            </tr>
        </thead>
        <tbody>
            <?php $__currentLoopData = $models; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $item): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                <tr>
                    <td><?php echo e($item->id); ?></td>
                    <td>
                        <?php echo Html::link(route('cabinet.admin.category.show', $item), $item->name); ?>

                    </td>
                    <td><?php echo e($item->slug); ?></td>
                    <td><?php echo $item->depth === 0 ? Html::tag('span', 'root', ['class' => 'badge badge-success']) : $item->depth; ?></td>
                    <td><?php echo e($item->children()->count()); ?></td>
                    <td>
                        <div class="btn-group">
                            <?php echo e(Html::link(route('cabinet.admin.category.first', $item), 'First', [
                                'class' => 'btn btn-sm btn-outline-secondary'
                            ])); ?>

                            <?php echo e(Html::link(route('cabinet.admin.category.up', $item), 'Up', [
                                'class' => 'btn btn-sm btn-outline-secondary'
                            ])); ?>

                            <?php echo e(Html::link(route('cabinet.admin.category.down', $item), 'Down', [
                                'class' => 'btn btn-sm btn-outline-secondary'
                            ])); ?>

                            <?php echo e(Html::link(route('cabinet.admin.category.last', $item), 'Last', [
                                'class' => 'btn btn-sm btn-outline-secondary'
                            ])); ?>

                        </div>
                    </td>
                    <td width="200">
                        <a href="<?php echo e(route('cabinet.admin.category.edit', $item->id)); ?>" class="btn btn-sm btn-info pull-left" style="margin-right: 3px;">
                            Edit
                        </a>

                        <?php echo Form::open([
                            'method' => 'DELETE',
                            'route' => ['cabinet.admin.category.destroy', $item->id],
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