@extends('layouts.admin')

@section('title', '| Edit Category')

@section('content')
    <div>
        <h1>Редактировать категорию {{ $category->name }}</h1>

        <hr>

        {{ Form::open([
            'method' => 'PUT',
            'url' => route('cabinet.admin.category.update', $category)
        ]) }}

        @if ($parent)
            <h4>Родительская категория</h4>
            <div class="mb-4">
                @include('cabinet.admin.category._face', ['category' => $parent])
            </div>
            <h4>Заполните поля для дочерней категории</h4>
            <hr />
        @endif

        <div class="form-group">
            {{ Form::label('parent_id', 'Родительская категория') }}
            <select name="parent_id" class="form-control">
                <option value="">Выбрать</option>
                @foreach ($list as $item)
                    <option value="{{ $item->id }}" {{ $item->id === $category->parent_id ? 'selected' : '' }}>
                        {!! $item->depth ? str_repeat('&mdash; ', $item->depth) : null !!}
                        {{ $item->name }}
                    </option>
                @endforeach
            </select>
            @if ($errors->has('parent_id'))
                <span class="invalid-feedback">
                        <strong>{{ $errors->first('parent_id') }}</strong>
                    </span>
            @endif
        </div>

        <div class="form-group">
            {{ Form::label('name', 'Название') }}
            {{ Form::text('name', old('name', $category->name), ['class' => 'form-control ' . (!$errors->has('name') ?: 'is-invalid')]) }}
            @if ($errors->has('name'))
                <span class="invalid-feedback">
                    <strong>{{ $errors->first('name') }}</strong>
                </span>
            @endif
        </div>

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
            {{ Form::label('title', 'Тайтл') }}
            {{ Form::text('title', old('title', $category->title), ['class' => 'form-control ' . (!$errors->has('title') ?: 'is-invalid')]) }}
            @if ($errors->has('title'))
                <span class="invalid-feedback">
                    <strong>{{ $errors->first('title') }}</strong>
                </span>
            @endif
        </div>

        <div class="form-group">
            {{ Form::label('description', 'Описание') }}
            {{ Form::textarea('description', old('description', $category->description), ['class' => 'form-control ' . (!$errors->has('description') ?: 'is-invalid')]) }}
            @if ($errors->has('description'))
                <span class="invalid-feedback">
                    <strong>{{ $errors->first('description') }}</strong>
                </span>
            @endif
        </div>

        {{ Form::submit('Обновить', ['class' => 'btn btn-primary']) }}

        {{ Form::close() }}
    </div>
@endsection