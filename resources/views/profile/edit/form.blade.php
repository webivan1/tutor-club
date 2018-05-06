@extends('layouts.profile')

@section('content')
    @parent

    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-body">
                    {{ Form::open(['method' => 'POST', 'url' => route('profile.edit.form')]) }}
                    <div class="form-group">
                        {{ Form::label('name', t('home.Username'), ['class' => 'bmd-label-floating']) }}
                        {{ Form::input('name', 'name', old('name', $user->name), [
                            'class' => 'form-control ' . (!$errors->has('name') ?: 'is-invalid')
                        ]) }}
                        @if ($errors->has('name'))
                            <span class="invalid-feedback">
                                    <strong>{{ $errors->first('name') }}</strong>
                                </span>
                        @endif
                    </div>

                    {{ Form::submit(t('home.Save'), ['class' => 'btn btn-raised btn-success']) }}
                    {{ Form::close() }}
                </div>
            </div>
        </div>
    </div>
@endsection