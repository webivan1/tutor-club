@extends('layouts.app')

@section('content')
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-6">
                <div class="card">
                    <div class="card-header">{{ t('home.FormLogin') }}</div>
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

                            <div class="checkbox">
                                <label>
                                    {{ Form::checkbox('remember', old('remember')) }} {{ t('home.labelRememberMe') }}
                                </label>
                            </div>

                            <div class="text-right">
                                <button type="submit" class="btn btn-raised btn-primary">
                                    {{ t('Login') }}
                                </button>
                            </div>
                        {{ Form::close() }}
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
