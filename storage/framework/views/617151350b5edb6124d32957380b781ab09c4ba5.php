<?php $__env->startSection('content'); ?>
    <h1 class="page-header">
        #<?php echo e($advert->id); ?> <?php echo e($advert->title); ?> - <?php echo e(__('home.editAdvertPhotoHeading')); ?>

    </h1>

    <hr />

    ##parent-placeholder-040f06fd774092478d450774f5ba30c5da78acc8##

    <?php if($files): ?>
        <div class="table-responsive">
            <table class="table">
                <?php $__currentLoopData = $files; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $file): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                    <tr>
                        <td width="200">
                            <img src="<?php echo e($file->file_path); ?>" alt="" class="img-fluid" />
                        </td>
                        <td>
                            <?php echo e(Form::open([
                                'method' => 'DELETE',
                                'url' => route('cabinet.advert.delete.file', [$advert, $file]),
                                'onsubmit' => 'return confirm("' . __('home.AreYouSure') . '");'
                            ])); ?>

                                <?php echo e(Form::submit('Delete', ['class' => 'btn btn-dander'])); ?>

                            <?php echo e(Form::close()); ?>

                        </td>
                    </tr>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
            </table>
        </div>
    <?php endif; ?>

    <div>
        <?php echo e(Form::open([
            'method' => 'PUT',
            'url' => route('cabinet.advert.update.files', $advert),
            'enctype' => 'multipart/form-data'
        ])); ?>


            <div class="form-group">
                <?php echo e(Form::file('photo[]', [
                    'multiple' => true,
                    'class' => 'form-control'
                ])); ?>

            </div>

            <?php echo e(Form::submit(__('home.ButtonUpload'), ['class' => 'btn btn-raised btn-success'])); ?>

        <?php echo e(Form::close()); ?>

    </div>
<?php $__env->stopSection(); ?>
<?php echo $__env->make('layouts.advert-edit', array_except(get_defined_vars(), array('__data', '__path')))->render(); ?>