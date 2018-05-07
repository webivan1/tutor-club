@extends('layouts.profile')

@section('content')
    @parent

    <div data-expire-token="{{ strtotime($profile->phone_token_expire) }}">
        <div class="row mb-2">
            <div class="col-md-7">
                {{ Form::open(['method' => 'POST', 'url' => route('profile.tutor.verify.form')]) }}
                    <div class="form-group">
                        {{ Form::label('code', t('Enter a code')) }}
                        {{ Form::text('code', '', [
                            'class' => 'form-control ' . (!$errors->has('code') ?: ' is-invalid')
                        ]) }}
                    </div>

                    {{ Form::submit(t('Check'), ['class' => 'btn btn-primary']) }}
                {{ Form::close() }}
            </div>
        </div>
        <div class="time-block text-danger">...</div>
    </div>
@endsection

@section('script.body')
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
            timeBlock.innerHTML = '<a href="{{ route('profile.tutor.verify.send') }}">{{ t('Resend') }}</a>';
          } else {
            timeBlock.innerText = '{{ t('You can send again after') }} ' + Math.round(timeToSendSms / 1000) + ' {{ t('sec') }}';
          }
        }, 1000);
    </script>
@endsection