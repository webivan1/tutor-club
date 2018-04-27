@extends('layouts.admin')

@section('title', '| Add lang')

@section('content')
    <div>
        <h1>Добавить язык</h1>

        <hr>

        {{ Form::open([
            'method' => 'POST',
            'url' => route('cabinet.admin.lang.store')
        ]) }}

        <div class="form-group">
            {{ Form::label('value', 'Значение языка (ru, en, de, fr)') }}
            {{ Form::text('value', old('value'), ['class' => 'form-control ' . (!$errors->has('value') ?: 'is-invalid')]) }}
            @if ($errors->has('value'))
                <span class="invalid-feedback">
                    <strong>{{ $errors->first('value') }}</strong>
                </span>
            @endif
        </div>

        <div class="form-group">
            {{ Form::label('name', 'Название языка на латинице') }}
            {{ Form::text('name', old('name'), ['class' => 'form-control ' . (!$errors->has('name') ?: 'is-invalid')]) }}
            @if ($errors->has('name'))
                <span class="invalid-feedback">
                    <strong>{{ $errors->first('name') }}</strong>
                </span>
            @endif
        </div>

        <div class="form-group">
            {{ Form::label('native', 'Оригинальное название') }}
            {{ Form::text('native', old('native'), ['class' => 'form-control ' . (!$errors->has('native') ?: 'is-invalid')]) }}
            @if ($errors->has('native'))
                <span class="invalid-feedback">
                    <strong>{{ $errors->first('native') }}</strong>
                </span>
            @endif
        </div>

        <div class="form-group">
            {{ Form::label('regional', 'Региональное название (ru_RU)') }}
            {{ Form::text('regional', old('regional'), ['class' => 'form-control ' . (!$errors->has('regional') ?: 'is-invalid')]) }}
            @if ($errors->has('regional'))
                <span class="invalid-feedback">
                    <strong>{{ $errors->first('regional') }}</strong>
                </span>
            @endif
        </div>

        {{ Form::submit('Добавить', ['class' => 'btn btn-primary']) }}

        {{ Form::close() }}
    </div>
@endsection