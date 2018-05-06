@extends('layouts.profile')

@section('content')
    @parent

    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-body">
                    {{ Form::open(['method' => 'PUT', 'url' => route('profile.email.send')]) }}
                    {{ Form::hidden('email', $email) }}

                    <div class="form-group">
                        {{ Form::label('token', t('home.WriteKeyFromMail'), ['class' => 'bmd-label-floating']) }}
                        {{ Form::text('token', old('token'), [
                            'class' => 'form-control ' . (!$errors->has('token') ?: 'is-invalid')
                        ]) }}
                        @if ($errors->has('token'))
                            <span class="invalid-feedback">
                                    <strong>{{ $errors->first('token') }}</strong>
                                </span>
                        @endif
                    </div>

                    {{ Form::submit(t('home.Verify'), ['class' => 'btn btn-raised btn-success']) }}
                    {{ Form::close() }}
                </div>
            </div>
        </div>
    </div>
@endsection