@extends('layouts.admin')

@section('content')
    <div>
        <h1>Редактировать #{{ $category->id }}</h1>

        <hr>

        {{ Form::open([
            'method' => 'PUT',
            'url' => route('cabinet.admin.media.category.update', $category)
        ]) }}

        <div class="form-group">
            {{ Form::label('slug', 'Алиас (урл)') }}
            {{ Form::text('slug', old('slug', $category->slug), ['class' => 'form-control ' . (!$errors->has('slug') ?: 'is-invalid')]) }}
            @if ($errors->has('slug'))
                <span class="invalid-feedback">
                    <strong>{{ $errors->first('slug') }}</strong>
                </span>
            @endif
        </div>

        <div class="form-group">
            {{ Form::label('title', 'Название (ключ)') }}
            {{ Form::text('title', old('title', $category->title), ['class' => 'form-control ' . (!$errors->has('title') ?: 'is-invalid')]) }}
            @if ($errors->has('title'))
                <span class="invalid-feedback">
                    <strong>{{ $errors->first('title') }}</strong>
                </span>
            @endif
        </div>

        <div class="form-group">
            {{ Form::label('heading', 'Заголовок (ключ)') }}
            {{ Form::text('heading', old('heading', $category->heading), ['class' => 'form-control ' . (!$errors->has('heading') ?: 'is-invalid')]) }}
            @if ($errors->has('heading'))
                <span class="invalid-feedback">
                    <strong>{{ $errors->first('heading') }}</strong>
                </span>
            @endif
        </div>

        <div class="form-group">
            {{ Form::label('content', 'Содержимое (ключ)') }}
            {{ Form::text('content', old('content', $category->content), ['class' => 'form-control ' . (!$errors->has('content') ?: 'is-invalid')]) }}
            @if ($errors->has('content'))
                <span class="invalid-feedback">
                    <strong>{{ $errors->first('content') }}</strong>
                </span>
            @endif
        </div>

        <div class="form-group">
            {{ Form::label('description', 'Описание (ключ)') }}
            {{ Form::text('description', old('description', $category->description), ['class' => 'form-control ' . (!$errors->has('description') ?: 'is-invalid')]) }}
            @if ($errors->has('description'))
                <span class="invalid-feedback">
                    <strong>{{ $errors->first('description') }}</strong>
                </span>
            @endif
        </div>

        <div class="form-group">
            {{ Form::label('status', 'Статус') }}
            {{ Form::select('status', ['' => 'Выбрать'] + $statuses, old('status', $category->status), ['class' => 'form-control ' . (!$errors->has('status') ?: 'is-invalid')]) }}
            @if ($errors->has('status'))
                <span class="invalid-feedback">
                        <strong>{{ $errors->first('status') }}</strong>
                    </span>
            @endif
        </div>

        {{ Form::submit('Обновить', ['class' => 'btn btn-primary']) }}

        {{ Form::close() }}
    </div>
@endsection