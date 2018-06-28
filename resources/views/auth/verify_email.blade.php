@extends('layouts.app')

@section('content')
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-6">
                <div class="card">
                    <div class="card-header">{{ t('Confirm your email') }}</div>
                    <div class="card-body">
                        {{ Form::open(['method' => 'PUT', 'url' => route('login.provider.email.update', $user), 'novalidate' => true]) }}
                            <div class="form-group">
                                {{ Form::label('email', 'Email', ['class' => 'bmd-label-floating']) }}
                                {{ Form::input('email', 'email', old('email', $provider->getEmail()), [
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
                                    {{ t('Confirm') }}
                                </button>
                            </div>
                        {{ Form::close() }}
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
