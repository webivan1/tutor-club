<?php if(Session::has('success')): ?>
    <div class="alert alert-success">
        <?php echo session('success'); ?>

    </div>
<?php endif; ?>

<?php if(Session::has('info')): ?>
    <div class="alert alert-info">
        <?php echo session('info'); ?>

    </div>
<?php endif; ?>

<?php if(Session::has('warning')): ?>
    <div class="alert alert-warning">
        <?php echo session('warning'); ?>

    </div>
<?php endif; ?>

<?php if(Session::has('danger')): ?>
    <div class="alert alert-danger">
        <?php echo session('danger'); ?>

    </div>
<?php endif; ?>

<?php if(Session::has('error')): ?>
    <div class="alert alert-danger">
        <?php echo session('error'); ?>

    </div>
<?php endif; ?>