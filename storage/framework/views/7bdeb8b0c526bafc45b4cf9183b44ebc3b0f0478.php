<!DOCTYPE html>
<html lang="<?php echo e(app()->getLocale()); ?>">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <!-- CSRF Token -->
    <meta name="csrf-token" content="<?php echo e(csrf_token()); ?>" />
    <meta name="description" content="<?php echo $__env->yieldContent('description'); ?>" />

    <title><?php echo $__env->yieldContent('title'); ?></title>

    <link href="https://fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet">

    <!-- Styles -->
    <link href="<?php echo e(asset('css/app.css')); ?>" rel="stylesheet">

    <?php echo $__env->yieldContent('script.head'); ?>
</head>
<body>
    <nav class="navbar navbar-expand-md navbar-light navbar-laravel">
        <div class="container">
            <a class="navbar-brand" href="<?php echo e(url('/')); ?>">
                <?php echo e(config('app.name', 'Laravel')); ?>

            </a>
            <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
            </button>

            <div class="collapse navbar-collapse" id="navbarSupportedContent">
                <!-- Left Side Of Navbar -->
                <ul class="navbar-nav mr-auto">

                </ul>

                <?php echo $__env->make('layouts._nav_right', array_except(get_defined_vars(), array('__data', '__path')))->render(); ?>
            </div>
        </div>
    </nav>

    <main class="app-content py-4">
        <div class="container">
            <?php $__env->startSection('breadcrumbs', Breadcrumbs::render()); ?>
            <?php echo $__env->yieldContent('breadcrumbs'); ?>

            <?php echo $__env->make('errors.flash_message', array_except(get_defined_vars(), array('__data', '__path')))->render(); ?>
            <?php echo $__env->make('errors.list', array_except(get_defined_vars(), array('__data', '__path')))->render(); ?>
        </div>

        <?php echo $__env->yieldContent('content'); ?>
    </main>

    <footer class="footer">
        <div class="container">
            @TutorOnline
        </div>
    </footer>

    <!-- Scripts -->
    <script src="<?php echo e(asset('js/app.js')); ?>"></script>
    <?php echo $__env->yieldContent('script.body'); ?>
</body>
</html>
