@extends('layouts.admin')

@section('content')
    <div>
        <h1>Добавить категорию новостей</h1>

        <hr>

        {{ Form::open([
            'method' => 'POST',
            'url' => route('cabinet.admin.media.category.store')
        ]) }}

        <div class="form-group">
            {{ Form::label('slug', 'Алиас (урл)') }}
            {{ Form::text('slug', old('slug'), ['class' => 'form-control ' . (!$errors->has('slug') ?: 'is-invalid')]) }}
            @if ($errors->has('slug'))
                <span class="invalid-feedback">
                    <strong>{{ $errors->first('slug') }}</strong>
                </span>
            @endif
        </div>

        <div class="form-group">
            {{ Form::label('title', 'Название (ключ)') }}
            {{ Form::text('title', old('title'), ['class' => 'form-control ' . (!$errors->has('title') ?: 'is-invalid')]) }}
            @if ($errors->has('title'))
                <span class="invalid-feedback">
                    <strong>{{ $errors->first('title') }}</strong>
                </span>
            @endif
        </div>

        <div class="form-group">
            {{ Form::label('heading', 'Заголовок (ключ)') }}
            {{ Form::text('heading', old('heading'), ['class' => 'form-control ' . (!$errors->has('heading') ?: 'is-invalid')]) }}
            @if ($errors->has('heading'))
                <span class="invalid-feedback">
                    <strong>{{ $errors->first('heading') }}</strong>
                </span>
            @endif
        </div>

        <div class="form-group">
            {{ Form::label('content', 'Содержимое (ключ)') }}
            {{ Form::text('content', old('content'), ['class' => 'form-control ' . (!$errors->has('content') ?: 'is-invalid')]) }}
            @if ($errors->has('content'))
                <span class="invalid-feedback">
                    <strong>{{ $errors->first('content') }}</strong>
                </span>
            @endif
        </div>

        <div class="form-group">
            {{ Form::label('description', 'Описание (ключ)') }}
            {{ Form::text('description', old('description'), ['class' => 'form-control ' . (!$errors->has('description') ?: 'is-invalid')]) }}
            @if ($errors->has('description'))
                <span class="invalid-feedback">
                    <strong>{{ $errors->first('description') }}</strong>
                </span>
            @endif
        </div>

        <div class='form-group'>
            {{ Form::label('status', 'Статус') }}
            {{ Form::select('status', ['' => 'Выбрать'] + $statuses, old('status'), [
                'class' => 'form-control'
            ]) }}
        </div>

        {{ Form::submit('Добавить', ['class' => 'btn btn-primary']) }}

        {{ Form::close() }}
    </div>
@endsection