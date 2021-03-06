@extends('layouts.profile')

@section('content')
    @parent

    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-body">
                    {{ Form::open(['method' => 'POST', 'url' => route('profile.password.form')]) }}
                    <div class="form-group">
                        {{ Form::label('password', t('home.WriteNewPassword'), ['class' => 'bmd-label-floating']) }}
                        {{ Form::input('password', 'password', '', [
                            'class' => 'form-control ' . (!$errors->has('password') ?: 'is-invalid')
                        ]) }}
                        @if ($errors->has('password'))
                            <span class="invalid-feedback">
                                    <strong>{{ $errors->first('password') }}</strong>
                                </span>
                        @endif
                    </div>

                    <div class="form-group">
                        {{ Form::label('password_confirmation', t('home.ReplayNewPassword'), ['class' => 'bmd-label-floating']) }}
                        {{ Form::input('password', 'password_confirmation', '', [
                            'class' => 'form-control'
                        ]) }}
                    </div>

                    {{ Form::submit(t('home.Save'), ['class' => 'btn btn-raised btn-success']) }}
                    {{ Form::close() }}
                </div>
            </div>
        </div>
    </div>
@endsection