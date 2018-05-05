@extends('layouts.admin')

@section('title', '| Add Category')

@section('content')
    <div>
        <h1>Добавить категорию</h1>

        <hr>

        {{ Form::open([
            'method' => 'POST',
            'url' => route('cabinet.admin.category.store')
        ]) }}

        @if ($parent)
            <h4>Родительская категория</h4>
            <div class="mb-4">
                @include('cabinet.admin.category._face', ['category' => $parent])
            </div>
            <h4>Заполните поля для дочерней категории</h4>
            <hr />

            {{ Form::hidden('parent_id', $parent->id) }}
        @endif

        <div class="form-group">
            {{ Form::label('name', 'Название') }}
            {{ Form::text('name', old('name'), ['class' => 'form-control ' . (!$errors->has('name') ?: 'is-invalid')]) }}
            @if ($errors->has('name'))
                <span class="invalid-feedback">
                    <strong>{{ $errors->first('name') }}</strong>
                </span>
            @endif
        </div>

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
            {{ Form::label('title', 'Тайтл SEO') }}
            {{ Form::text('title', old('title'), ['class' => 'form-control ' . (!$errors->has('title') ?: 'is-invalid')]) }}
            @if ($errors->has('title'))
                <span class="invalid-feedback">
                    <strong>{{ $errors->first('title') }}</strong>
                </span>
            @endif
        </div>

        <div class="form-group">
            {{ Form::label('description', 'Описание SEO') }}
            {{ Form::textarea('description', old('description'), ['class' => 'form-control ' . (!$errors->has('description') ?: 'is-invalid')]) }}
            @if ($errors->has('description'))
                <span class="invalid-feedback">
                    <strong>{{ $errors->first('description') }}</strong>
                </span>
            @endif
        </div>

        <div class="form-group">
            {{ Form::label('content', 'Контент') }}
            {{ Form::textarea('content', old('content'), ['class' => 'form-control ' . (!$errors->has('content') ?: 'is-invalid')]) }}
            @if ($errors->has('content'))
                <span class="invalid-feedback">
                    <strong>{{ $errors->first('content') }}</strong>
                </span>
            @endif
        </div>

        {{ Form::submit('Добавить', ['class' => 'btn btn-primary']) }}

        {{ Form::close() }}
    </div>
@endsection