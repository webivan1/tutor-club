<?php if($profile): ?>
    <?php if($profile->isBlocked()): ?>
        <?php echo $__env->make('tutor.status.blocked', array_except(get_defined_vars(), array('__data', '__path')))->render(); ?>
    <?php else: ?>
        <?php echo $__env->make('tutor.status.active', compact('profile'), array_except(get_defined_vars(), array('__data', '__path')))->render(); ?>
    <?php endif; ?>
<?php else: ?>
    <?php echo $__env->make('tutor.status.empty', array_except(get_defined_vars(), array('__data', '__path')))->render(); ?>
<?php endif; ?>