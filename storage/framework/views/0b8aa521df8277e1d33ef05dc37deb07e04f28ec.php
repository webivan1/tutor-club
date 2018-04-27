<?php $__env->startSection('content'); ?>
    ##parent-placeholder-040f06fd774092478d450774f5ba30c5da78acc8##

    <div class="container" data-expire-token="<?php echo e(strtotime($profile->phone_token_expire)); ?>">
        <div class="row mb-2">
            <div class="col-md-7">
                <?php echo e(Form::open(['method' => 'POST', 'url' => route('profile.tutor.verify.form')])); ?>

                    <div class="form-group">
                        <?php echo e(Form::label('code', 'Введите код')); ?>

                        <?php echo e(Form::text('code', '', [
                            'class' => 'form-control ' . (!$errors->has('code') ?: ' is-invalid')
                        ])); ?>

                    </div>

                    <?php echo e(Form::submit('Проверить', ['class' => 'btn btn-primary'])); ?>

                <?php echo e(Form::close()); ?>

            </div>
        </div>
        <div class="time-block text-danger">...</div>
    </div>
<?php $__env->stopSection(); ?>

<?php $__env->startSection('script.body'); ?>
    <script type="text/javascript">
        var expireToken = (document.querySelector('[data-expire-token]').getAttribute('data-expire-token')) * 1000;
        var selfTime = (new Date()).getTime();
        var timeToSendSms = expireToken - selfTime;
        var interval;
        var timeBlock = document.querySelector('.time-block');

        interval = setInterval(function () {
          timeToSendSms -= 1000;
          if (timeToSendSms <= 0) {
            clearInterval(interval);
            timeBlock.innerHTML = '<a href="<?php echo e(route('profile.tutor.verify.send')); ?>">Отправить повторно</a>';
          } else {
            timeBlock.innerText = 'Повторно можно отправить через ' + Math.round(timeToSendSms / 1000) + ' сек';
          }
        }, 1000);
    </script>
<?php $__env->stopSection(); ?>
<?php echo $__env->make('layouts.profile', array_except(get_defined_vars(), array('__data', '__path')))->render(); ?>