<!-- Right Side Of Navbar -->
<ul class="navbar-nav ml-auto">
    <li class="nav-item dropdown mr-md-2">
        <a id="navbarDropdownLanguages" class="nav-link dropdown-toggle not-after" href="#" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
            <div class="flag flag-<?php echo e(app()->getLocale()); ?>"></div>
        </a>
        <div class="dropdown-menu dropdown-menu-right" aria-labelledby="navbarDropdownLanguages">
            <?php $__currentLoopData = LaravelLocalization::getSupportedLocales(); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $localeCode => $properties): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                <a class="dropdown-item" rel="alternate" hreflang="<?php echo e($localeCode); ?>" href="<?php echo e(LaravelLocalization::getLocalizedURL($localeCode, null, [], true)); ?>">
                    <div class="flag flag-<?php echo e($localeCode); ?>"></div>
                    <?php echo e($properties['native']); ?>

                </a>
            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
        </div>
    </li>

    <!-- Authentication Links -->
    <?php if(auth()->guard()->guest()): ?>
        <li><a class="nav-link" href="<?php echo e(route('login')); ?>"><?php echo e(t('home.Login')); ?></a></li>
        <li><a class="nav-link" href="<?php echo e(route('register')); ?>"><?php echo e(t('home.Register')); ?></a></li>
    <?php else: ?>
        <li class="nav-item dropdown">
            <a id="navbarDropdown" class="dropdown-toggle nav-link not-after btn mb-0" href="#" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false" v-pre>
                <i class="material-icons">face</i>
            </a>

            <div class="dropdown-menu dropdown-menu-right" aria-labelledby="navbarDropdown">
                <span class="dropdown-header"><?php echo e(Auth::user()->name); ?></span>
                <a class="dropdown-item" href="<?php echo e(route('cabinet.home')); ?>">
                    <?php echo e(t('home.Cabinet')); ?>

                </a>
                <a class="dropdown-item" href="<?php echo e(route('profile.home')); ?>">
                    <?php echo e(t('home.Profile')); ?>

                </a>
                <div class="dropdown-divider"></div>
                <a class="dropdown-item" href="<?php echo e(route('logout')); ?>">
                    <?php echo e(t('home.Logout')); ?>

                </a>
            </div>
        </li>
    <?php endif; ?>
</ul>