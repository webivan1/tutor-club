@extends('layouts.profile')

@section('content')
    @parent

    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-8">
                <div class="card">
                    <div class="card-body">
                        {{ Form::open(['method' => 'GET', 'url' => route('profile.email.send')]) }}
                            <div class="form-group">
                                {{ Form::label('email', t('home.WriteNewEmail'), ['class' => 'bmd-label-floating']) }}
                                {{ Form::input('email', 'email', old('email'), [
                                    'class' => 'form-control ' . (!$errors->has('email') ?: 'is-invalid')
                                ]) }}
                                @if ($errors->has('email'))
                                    <span class="invalid-feedback">
                                    <strong>{{ $errors->first('email') }}</strong>
                                </span>
                                @endif
                            </div>

                            {{ Form::submit(t('home.Save'), ['class' => 'btn btn-raised btn-success']) }}
                        {{ Form::close() }}
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection