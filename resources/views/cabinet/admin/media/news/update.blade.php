@extends('layouts.admin')

@section('content')
    <div>
        <h1>Редактировать {{ $news->heading }}</h1>

        <hr>

        {{ Form::open([
            'method' => 'PUT',
            'url' => route('cabinet.admin.media.news.update', $news),
            'enctype' => 'multipart/form-data'
        ]) }}

        <div class="form-group">
            {{ Form::label('slug', 'Алиас (урл)') }}
            {{ Form::text('slug', old('slug', $news->slug), ['class' => 'form-control ' . (!$errors->has('slug') ?: 'is-invalid')]) }}
            @if ($errors->has('slug'))
                <span class="invalid-feedback">
                    <strong>{{ $errors->first('slug') }}</strong>
                </span>
            @endif
        </div>

        <div class="form-group">
            {{ Form::label('heading', 'Заголовок') }}
            {{ Form::text('heading', old('heading', $news->heading), ['class' => 'form-control ' . (!$errors->has('heading') ?: 'is-invalid')]) }}
            @if ($errors->has('heading'))
                <span class="invalid-feedback">
                    <strong>{{ $errors->first('heading') }}</strong>
                </span>
            @endif
        </div>

        <div class='form-group'>
            {{ Form::label('category_id', 'Категория') }}
            {{ Form::select('category_id', ['' => 'Выбрать'] + $newsCategory, old('category_id', $news->category_id), [
                'class' => 'form-control'
            ]) }}
        </div>

        <div class="form-group">
            {{ Form::label('content', 'Содержимое') }}
            {{ Form::textarea('content', old('content', $news->content), ['class' => 'form-control ' . (!$errors->has('content') ?: 'is-invalid')]) }}
            @if ($errors->has('content'))
                <span class="invalid-feedback">
                    <strong>{{ $errors->first('content') }}</strong>
                </span>
            @endif
        </div>

        <div class="form-group">
            @if ($news->id && $news->image)
                <div class="mb-2">
                    {{ Html::image(Storage::url($news->image->getPreset('200x250')) . '?t=' . time(), 'Фото', [
                        'class' => 'img-thumbnail'
                    ]) }}
                </div>
            @endif

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
            {{ Form::text('title', old('title', $news->title), ['class' => 'form-control ' . (!$errors->has('title') ?: 'is-invalid')]) }}
            @if ($errors->has('title'))
                <span class="invalid-feedback">
                    <strong>{{ $errors->first('title') }}</strong>
                </span>
            @endif
        </div>

        <div class="form-group">
            {{ Form::label('description', 'Описание') }}
            {{ Form::textarea('description', old('description', $news->description), ['class' => 'form-control ' . (!$errors->has('description') ?: 'is-invalid')]) }}
            @if ($errors->has('description'))
                <span class="invalid-feedback">
                    <strong>{{ $errors->first('description') }}</strong>
                </span>
            @endif
        </div>

        <div class='form-group'>
            {{ Form::label('lang', 'Язык') }}
            {{ Form::select('lang', ['' => 'Выбрать'] + \App\Helpers\LangHelper::langList(), old('lang', $news->lang), [
                'class' => 'form-control'
            ]) }}
        </div>

        <div class='form-group'>
            {{ Form::label('status', 'Статус') }}
            {{ Form::select('status', ['' => 'Выбрать'] + $statuses, old('status', $news->status), [
                'class' => 'form-control'
            ]) }}
        </div>

        <div class="form-group">
            {{ Form::label('published_at', 'Дата публикации') }}
            {{ Form::date('published_at', old('published_at', date('Y-m-d', strtotime($news->published_at))), ['class' => 'form-control ' . (!$errors->has('published_at') ?: 'is-invalid')]) }}
            @if ($errors->has('published_at'))
                <span class="invalid-feedback">
                    <strong>{{ $errors->first('published_at') }}</strong>
                </span>
            @endif
        </div>

        {{ Form::submit('Обновить', ['class' => 'btn btn-primary']) }}

        {{ Form::close() }}
    </div>
@endsection