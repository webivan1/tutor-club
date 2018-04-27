@extends('layouts.admin')

@section('title', 'Add attribute')

@section('content')
    <h1>
        Добавить атрибут к категории {{ $category->name }}
    </h1>

    <hr />

    <div class="row">
        <div class="col-md-7">
            {{ Form::open(['method' => 'POST', 'url' => route('cabinet.admin.category.attribute.store', $category)]) }}
                <div class="form-group">
                    {{ Form::label('label', 'Название поля') }}
                    {{ Form::text('label', old('label'), [
                        'class' => 'form-control ' . ($errors->has('label') ? 'is-invalid' : null)
                    ]) }}
                    @if ($errors->has('label'))
                        <div class="invalid-feedback">
                            <b>{{ $errors->get('label')->first() }}</b>
                        </div>
                    @endif
                </div>

                <div class="form-group">
                    {{ Form::label('type', 'Тип поля') }}
                    {{ Form::select('type', \App\Entity\Attribute::types(), old('type'), [
                        'class' => 'form-control ' . ($errors->has('type') ? 'is-invalid' : null)
                    ]) }}
                    @if ($errors->has('type'))
                        <div class="invalid-feedback">
                            <b>{{ $errors->get('type')->first() }}</b>
                        </div>
                    @endif
                </div>

                <div class="form-group">
                    {{ Form::label('variants', 'Варианты') }}
                    {{ Form::textarea('variants', old('variants'), [
                        'class' => 'form-control ' . ($errors->has('variants') ? 'is-invalid' : null)
                    ]) }}
                    @if ($errors->has('variants'))
                        <div class="invalid-feedback">
                            <b>{{ $errors->get('variants')->first() }}</b>
                        </div>
                    @endif
                </div>

                <div class="form-group">
                    {{ Form::label('sort', 'Сортировка') }}
                    {{ Form::text('sort', old('sort'), [
                        'class' => 'form-control ' . ($errors->has('sort') ? 'is-invalid' : null)
                    ]) }}
                    @if ($errors->has('sort'))
                        <div class="invalid-feedback">
                            <b>{{ $errors->get('sort')->first() }}</b>
                        </div>
                    @endif
                </div>

                <div class="form-group">
                    <label>
                        Обязательное поле
                        {{ Form::checkbox('required', 1, old('required', 0)) }}
                    </label>
                </div>

                {{ Form::submit('Добавить', ['class' => 'btn btn-primary']) }}
            {{ Form::close() }}
        </div>
    </div>

@endsection