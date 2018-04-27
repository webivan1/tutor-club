@extends('layouts.admin')

@section('title', '| Add permission')

@section('content')
    <div>
        <h1>Добавить разрешение</h1>

        <hr>

        {{ Form::open([
            'method' => 'POST',
            'url' => route('cabinet.admin.permission.store')
        ]) }}

        <div class="form-group">
            {{ Form::label('title', 'Название разрешения') }}
            {{ Form::text('title', old('title'), ['class' => 'form-control ' . (!$errors->has('title') ?: 'is-invalid')]) }}
            @if ($errors->has('title'))
                <span class="invalid-feedback">
                    <strong>{{ $errors->first('title') }}</strong>
                </span>
            @endif
        </div>

        <div class="form-group">
            {{ Form::label('name', 'Разрешение') }}
            {{ Form::text('name', old('name'), ['class' => 'form-control ' . (!$errors->has('name') ?: 'is-invalid')]) }}
            @if ($errors->has('name'))
                <span class="invalid-feedback">
                    <strong>{{ $errors->first('name') }}</strong>
                </span>
            @endif
        </div>

        {{ Form::submit('Добавить', ['class' => 'btn btn-primary']) }}

        {{ Form::close() }}
    </div>
@endsection