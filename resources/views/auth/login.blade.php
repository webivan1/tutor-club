@extends('layouts.app')

@section('content')
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-6">
                <div class="card">
                    <div class="card-header">{{ t('home.FormLogin') }}</div>

                    @include('auth._social_links')

                    <div class="card-body">
                        {{ Form::open(['method' => 'POST', 'url' => route('login'), 'novalidate' => true]) }}
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

                            <div class="form-group">
                                {{ Form::label('password', t('home.LabelPassword'), ['class' => 'bmd-label-floating']) }}
                                {{ Form::input('password', 'password', old('password'), [
                                    'class' => 'form-control' . ($errors->has('password') ? ' is-invalid' : ''),
                                    'validate' => true
                                ]) }}
                                @if ($errors->has('password'))
                                    <span class="invalid-feedback">
                                        <strong>{{ $errors->first('password') }}</strong>
                                    </span>
                                @endif
                            </div>

                            <checkbox
                                name="remember"
                                label="{{ t('home.labelRememberMe') }}"
                                checked="{{ old('remember', 0) }}"
                            ></checkbox>

                            <div class="text-right">
                                <button type="submit" class="mdc-button mdc-button--raised">
                                    {{ t('Login') }}
                                </button>
                            </div>
                        {{ Form::close() }}
                    </div>

                    <div class="card-footer">
                        <div>
                            {{ t('Do you have account') }}? <a href="{{ route('register') }}" class="card-link">
                                {{ t('You can register') }}
                            </a>
                        </div>
                        <div>
                            {{ t('Did you forget password') }}? <a href="{{ route('password.request') }}" class="card-link">
                                {{ t('You can restore password') }}
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
