@extends('layouts.app')

@section('content')
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-6">
                <div class="card">
                    <div class="card-header">{{ t('Reset Password') }}</div>

                    <div class="card-body">
                        {{ Form::open(['method' => 'POST', 'url' => route('password.request'), 'novalidate' => true]) }}

                            {{ Form::hidden('token', $token) }}

                            <div class="form-group">
                                {{ Form::label('email', 'Email', ['class' => 'bmd-label-floating']) }}
                                {{ Form::input('email', 'email', old('email', $email), [
                                    'class' => 'form-control' . ($errors->has('email') ? ' is-invalid' : ''),
                                    'validate' => true
                                ]) }}
                                @if ($errors->has('email'))
                                    <span class="invalid-feedback">
                                        <strong>{{ $errors->first('email') }}</strong>
                                    </span>
                                @endif
                            </div>

                            <div class="form-group">
                                {{ Form::label('password', t('Password'), ['class' => 'bmd-label-floating']) }}
                                {{ Form::input('password', 'password', '', [
                                    'class' => 'form-control' . ($errors->has('password') ? ' is-invalid' : ''),
                                    'validate' => true
                                ]) }}
                                @if ($errors->has('password'))
                                    <span class="invalid-feedback">
                                        <strong>{{ $errors->first('password') }}</strong>
                                    </span>
                                @endif
                            </div>

                            <div class="form-group">
                                {{ Form::label('password_confirmation', t('Confirm Password'), ['class' => 'bmd-label-floating']) }}
                                {{ Form::input('password', 'password_confirmation', '', [
                                    'class' => 'form-control' . ($errors->has('password_confirmation') ? ' is-invalid' : ''),
                                    'validate' => true
                                ]) }}
                                @if ($errors->has('password_confirmation'))
                                    <span class="invalid-feedback">
                                        <strong>{{ $errors->first('password_confirmation') }}</strong>
                                    </span>
                                @endif
                            </div>

                            <div class="text-right">
                                <button type="submit" class="mdc-button mdc-button--raised">
                                    {{ t('Reset Password') }}
                                </button>
                            </div>
                        {{ Form::close() }}
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
