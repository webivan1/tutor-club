@extends('layouts.admin')

@section('content')
    <div>
        <h1>Добавить новость</h1>

        <hr>

        {{ Form::open([
            'method' => 'POST',
            'url' => route('cabinet.admin.media.news.store'),
            'enctype' => 'multipart/form-data'
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
            {{ Form::label('heading', 'Заголовок') }}
            {{ Form::text('heading', old('heading'), ['class' => 'form-control ' . (!$errors->has('heading') ?: 'is-invalid')]) }}
            @if ($errors->has('heading'))
                <span class="invalid-feedback">
                    <strong>{{ $errors->first('heading') }}</strong>
                </span>
            @endif
        </div>

        <div class='form-group'>
            {{ Form::label('category_id', 'Категория') }}
            {{ Form::select('category_id', ['' => 'Выбрать'] + $newsCategory, old('category_id'), [
                'class' => 'form-control'
            ]) }}
        </div>

        <div class="form-group">
            {{ Form::label('content', 'Содержимое') }}
            {{ Form::textarea('content', old('content'), ['class' => 'form-control ' . (!$errors->has('content') ?: 'is-invalid')]) }}
            @if ($errors->has('content'))
                <span class="invalid-feedback">
                    <strong>{{ $errors->first('content') }}</strong>
                </span>
            @endif
        </div>

        <div class="form-group">
            {{ Form::label('photo', 'Фото') }}
            {{ Form::file('photo', ['class' => 'form-control ' . (!$errors->has('photo') ?: 'is-invalid')]) }}
            @if ($errors->has('photo'))
                <span class="invalid-feedback">
                    <strong>{{ $errors->first('photo') }}</strong>
                </span>
            @endif
        </div>

        <div class="form-group">
            {{ Form::label('title', 'Тайтл') }}
            {{ Form::text('title', old('title'), ['class' => 'form-control ' . (!$errors->has('title') ?: 'is-invalid')]) }}
            @if ($errors->has('title'))
                <span class="invalid-feedback">
                    <strong>{{ $errors->first('title') }}</strong>
                </span>
            @endif
        </div>

        <div class="form-group">
            {{ Form::label('description', 'Описание') }}
            {{ Form::textarea('description', old('description'), ['class' => 'form-control ' . (!$errors->has('description') ?: 'is-invalid')]) }}
            @if ($errors->has('description'))
                <span class="invalid-feedback">
                    <strong>{{ $errors->first('description') }}</strong>
                </span>
            @endif
        </div>

        <div class='form-group'>
            {{ Form::label('lang', 'Язык') }}
            {{ Form::select('lang', ['' => 'Выбрать'] + \App\Helpers\LangHelper::langList(), old('lang'), [
                'class' => 'form-control'
            ]) }}
        </div>

        <div class='form-group'>
            {{ Form::label('status', 'Статус') }}
            {{ Form::select('status', ['' => 'Выбрать'] + $statuses, old('status'), [
                'class' => 'form-control'
            ]) }}
        </div>

        <div class="form-group">
            {{ Form::label('published_at', 'Дата публикации') }}
            {{ Form::date('published_at', old('published_at'), ['class' => 'form-control ' . (!$errors->has('published_at') ?: 'is-invalid')]) }}
            @if ($errors->has('published_at'))
                <span class="invalid-feedback">
                    <strong>{{ $errors->first('published_at') }}</strong>
                </span>
            @endif
        </div>

        {{ Form::submit('Добавить', ['class' => 'btn btn-primary']) }}

        {{ Form::close() }}
    </div>
@endsection