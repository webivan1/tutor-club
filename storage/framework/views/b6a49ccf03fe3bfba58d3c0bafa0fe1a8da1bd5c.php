<!DOCTYPE html>
<html lang="<?php echo e(app()->getLocale()); ?>">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <!-- CSRF Token -->
    <meta name="csrf-token" content="<?php echo e(csrf_token()); ?>">

    <title><?php echo e(config('app.name', 'Laravel')); ?> | Admin panel <?php echo $__env->yieldContent('title'); ?></title>

    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/css/bootstrap.min.css" integrity="sha384-Gn5384xqQ1aoWXA+058RXPxPg6fy4IWvTNh0E263XmFcJlSAwiGgFAW/dAiS6JXm" crossorigin="anonymous">
</head>
<body>
    <nav class="navbar navbar-expand-md navbar-dark bg-primary">
        <div class="container">
            <a class="navbar-brand" href="<?php echo e(url('/')); ?>">
                Admin panel
            </a>
            <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
            </button>

            <div class="collapse navbar-collapse" id="navbarSupportedContent">
                <!-- Left Side Of Navbar -->
                <ul class="navbar-nav mr-auto">
                    <?php if(Auth::user()->isA('super_admin')): ?>
                        <li class="nav-item dropdown">
                            <a class="nav-link dropdown-toggle" href="#" id="navbarDropdown" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                Управление доступами
                            </a>
                            <div class="dropdown-menu" aria-labelledby="navbarDropdown">
                                <a class="dropdown-item" href="<?php echo e(route('cabinet.admin.users.index')); ?>">
                                    Список пользователей
                                </a>
                                <a class="dropdown-item" href="<?php echo e(route('cabinet.admin.role.index')); ?>">
                                    Роли
                                </a>
                                <a class="dropdown-item" href="<?php echo e(route('cabinet.admin.permission.index')); ?>">
                                    Разрешения
                                </a>
                            </div>
                        </li>
                    <?php endif; ?>

                    <?php if(Auth::user()->hasRole('moderator')): ?>
                        <li class="nav-item">
                            <a class="nav-link" href="<?php echo e(route('cabinet.admin.tutor.index')); ?>">
                                Репетиторы
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="<?php echo e(route('cabinet.admin.advert.index')); ?>">
                                Объявления
                            </a>
                        </li>
                    <?php endif; ?>

                    <?php if(Auth::user()->hasRole('content')): ?>
                        <li class="nav-item">
                            <a class="nav-link" href="<?php echo e(route('cabinet.admin.category.index')); ?>">
                                Категории
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="<?php echo e(route('cabinet.admin.translate.index')); ?>">
                                Переводы
                            </a>
                        </li>
                    <?php endif; ?>
                </ul>

                <ul class="navbar-nav ml-auto">
                    <li class="nav-item dropdown">
                        <a id="navbarDropdown" class="dropdown-toggle nav-link" href="#" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false" v-pre>
                            <?php echo e(Auth::user()->name); ?>

                        </a>
                        <div class="dropdown-menu dropdown-menu-right" aria-labelledby="navbarDropdown">
                            <a class="dropdown-item" href="<?php echo e(route('logout')); ?>">
                                <?php echo e(t('Logout')); ?>

                            </a>
                        </div>
                    </li>
                </ul>
            </div>
        </div>
    </nav>

    <main class="app-content py-4">
        <div class="container">
            <?php $__env->startSection('breadcrumbs', Breadcrumbs::render()); ?>
            <?php echo $__env->yieldContent('breadcrumbs'); ?>

            <?php echo $__env->make('errors.flash_message', array_except(get_defined_vars(), array('__data', '__path')))->render(); ?>
            <?php echo $__env->make('errors.list', array_except(get_defined_vars(), array('__data', '__path')))->render(); ?>

            <?php echo $__env->yieldContent('content'); ?>
        </div>
    </main>

    <footer>

    </footer>

    <script src="https://code.jquery.com/jquery-3.2.1.slim.min.js" integrity="sha384-KJ3o2DKtIkvYIK3UENzmM7KCkRr/rE9/Qpg6aAZGJwFDMVNA/GpGFF93hXpG5KkN" crossorigin="anonymous"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.12.9/umd/popper.min.js" integrity="sha384-ApNbgh9B+Y1QKtv3Rn7W3mgPxhU9K/ScQsAP7hUibX39j7fakFPskvXusvfa0b4Q" crossorigin="anonymous"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/js/bootstrap.min.js" integrity="sha384-JZR6Spejh4U02d8jOt6vLEHfe/JQGiRRSQQxSfFWpi1MquVdAyjUar5+76PVCmYl" crossorigin="anonymous"></script>
    <?php echo $__env->yieldContent('script.body'); ?>
</body>
</html>
