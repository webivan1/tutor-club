@extends('layouts.app')

@section('content')
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-6">
                <div class="card">
                    <div class="card-header">{{ t('Reset Password') }}</div>

                    <div class="card-body">
                        {{ Form::open(['method' => 'POST', 'url' => route('password.email'), 'novalidate' => true]) }}
                            <div class="form-group">
                                {{ Form::label('email', 'Email', ['class' => 'bmd-label-floating']) }}
                                {{ Form::input('email', 'email', old('email'), [
                                    'class' => 'form-control' . ($errors->has('email') ? ' is-invalid' : ''),
                                    'validate' => true
                                ]) }}
                                @if ($errors->has('email'))
                                    <span class="invalid-feedback">
                                        <strong>{{ $errors->first('email') }}</strong>
                                    </span>
                                @endif
                            </div>

                            <div class="text-right">
                                <button type="submit" class="mdc-button mdc-button--raised">
                                    {{ t('Send Password Reset Link') }}
                                </button>
                            </div>
                        {{ Form::close() }}
                    </div>

                    <div class="card-footer">
                        <div>
                            {{ t('Did you remember password') }}? <a href="{{ route('login') }}" class="card-link">
                                {{ t('You can login') }}
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
