<?php if(Session::has('success')): ?>
    <div class="alert alert-success my-2">
        <?php echo session('success'); ?>

    </div>
<?php endif; ?>

<?php if(Session::has('info')): ?>
    <div class="alert alert-info my-2">
        <?php echo session('info'); ?>

    </div>
<?php endif; ?>

<?php if(Session::has('warning')): ?>
    <div class="alert alert-warning my-2">
        <?php echo session('warning'); ?>

    </div>
<?php endif; ?>

<?php if(Session::has('danger')): ?>
    <div class="alert alert-danger my-2">
        <?php echo session('danger'); ?>

    </div>
<?php endif; ?>

<?php if(Session::has('error')): ?>
    <div class="alert alert-danger my-2">
        <?php echo session('error'); ?>

    </div>
<?php endif; ?>