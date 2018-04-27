<?php $__env->startSection('title', '| Category ' . $category->name); ?>

<?php $__env->startSection('content'); ?>
    <h1 class="page-header">
        Категория <?php echo e($category->name); ?>

    </h1>

    <hr />

    <div class="mb-4 clearfix">
        <?php echo Html::link(route('cabinet.admin.category.edit', $category), 'Редактировать', [
            'class' => 'btn btn-info'
        ]); ?>

        <?php echo Html::link(route('cabinet.admin.category.create', ['id' => $category]), 'Добавить дочерних', [
            'class' => 'btn btn-success'
        ]); ?>

        <?php echo Html::link(route('cabinet.admin.category.attribute.create', $category), 'Добавить атрибут', [
            'class' => 'btn btn-success'
        ]); ?>

        <?php echo Html::link(route('cabinet.admin.category.destroy', $category), 'Удалить', [
            'class' => 'btn btn-danger pull-right',
            'onclick' => 'return confirm("Вы уверены?");'
        ]); ?>

    </div>

    <?php echo $__env->make('cabinet.admin.category._face', compact('category'), array_except(get_defined_vars(), array('__data', '__path')))->render(); ?>

    <nav>
        <div class="nav nav-tabs" id="nav-tab" role="tablist">
            <a class="nav-item nav-link active" id="nav-home-tab" data-toggle="tab" href="#nav-home" role="tab" aria-controls="nav-home" aria-selected="true">Дочерние категории</a>
            <a class="nav-item nav-link" id="nav-profile-tab" data-toggle="tab" href="#nav-profile" role="tab" aria-controls="nav-profile" aria-selected="false">Атрибуты</a>
        </div>
    </nav>
    <div class="tab-content" id="nav-tabContent">
        <div class="tab-pane fade show active" id="nav-home" role="tabpanel" aria-labelledby="nav-home-tab">
            <?php echo $__env->make('cabinet.admin.category._list', [
                'models' => $category->children()->defaultOrder()->withDepth()->get()
            ], array_except(get_defined_vars(), array('__data', '__path')))->render(); ?>
        </div>
        <div class="tab-pane fade" id="nav-profile" role="tabpanel" aria-labelledby="nav-profile-tab">
            <?php echo $__env->make('attribute.list', [
                'models' => $category->allAttributes(),
                'category' => $category
            ], array_except(get_defined_vars(), array('__data', '__path')))->render(); ?>
        </div>
    </div>
<?php $__env->stopSection(); ?>
<?php echo $__env->make('layouts.admin', array_except(get_defined_vars(), array('__data', '__path')))->render(); ?>