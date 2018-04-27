<!DOCTYPE html>
<html lang="<?php echo e(app()->getLocale()); ?>">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <!-- CSRF Token -->
    <meta name="csrf-token" content="<?php echo e(csrf_token()); ?>">

    <title><?php echo e(config('app.name', 'Laravel')); ?> | Cabinet | <?php echo $__env->yieldContent('title'); ?></title>

    <link href="https://fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet">

    <!-- Styles -->
    <link href="<?php echo e(asset('css/app.css')); ?>" rel="stylesheet">

    <?php echo $__env->yieldContent('script.head'); ?>
</head>
<body>
    <div class="bmd-layout-container bmd-drawer-f-l <?php if (! empty(trim($__env->yieldContent('not-drawers')))): ?> <?php else: ?> bmd-drawer-in <?php endif; ?>">
        <header class="bmd-layout-header">
            <nav class="navbar navbar-expand-md navbar-light navbar-laravel">
                <div class="container">
                    <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
                        <span class="navbar-toggler-icon"></span>
                    </button>

                    <div class="collapse navbar-collapse" id="navbarSupportedContent">

                        <?php if (! empty(trim($__env->yieldContent('not-drawers')))): ?>

                        <?php else: ?>
                            <!-- Left Side Of Navbar -->
                            <ul class="navbar-nav mr-auto">
                                <li class="nav-item">
                                    <a class="nav-link" href="#" role="button"  data-toggle="drawer" data-target="#dw-s1">
                                        <i class="material-icons">menu</i>
                                    </a>
                                </li>
                            </ul>
                        <?php endif; ?>

                        <?php echo $__env->make('layouts._nav_right', array_except(get_defined_vars(), array('__data', '__path')))->render(); ?>
                    </div>
                </div>
            </nav>
        </header>
        <div id="dw-s1" class="bmd-layout-drawer bg-faded">
            <header>
                <a class="navbar-brand">
                    <?php echo e(config('app.name', 'SiteName')); ?>

                </a>
            </header>
            <ul class="list-group">
                <?php echo $__env->yieldContent('nav-left'); ?>
            </ul>
        </div>
        <main class="bmd-layout-content">
            <div class="container pt-3 pb-3">
                <?php $__env->startSection('breadcrumbs', Breadcrumbs::render()); ?>
                <?php echo $__env->yieldContent('breadcrumbs'); ?>

                <?php echo $__env->make('errors.flash_message', array_except(get_defined_vars(), array('__data', '__path')))->render(); ?>
                <?php echo $__env->make('errors.list', array_except(get_defined_vars(), array('__data', '__path')))->render(); ?>

                <?php echo $__env->yieldContent('content'); ?>
            </div>
        </main>
    </div>

    <!-- Scripts -->
    <script src="<?php echo e(asset('js/app.js')); ?>" defer></script>
    <?php echo $__env->yieldContent('script.body'); ?>
</body>
</html>
