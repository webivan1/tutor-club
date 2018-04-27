@extends('layouts.admin')

@section('title', '| Edit translate')

@section('content')
    <div>
        <h1>Редактировать перевод #{{ $translate->name }}</h1>

        <hr>

        {{ Form::open([
            'method' => 'PUT',
            'url' => route('cabinet.admin.translate.update', $translate)
        ]) }}

        <div class="form-group">
            {{ Form::label('name', 'Ключ') }}
            {{ Form::text('name', old('name', $translate->name), ['class' => 'form-control ' . (!$errors->has('name') ?: 'is-invalid')]) }}
            @if ($errors->has('name'))
                <span class="invalid-feedback">
                    <strong>{{ $errors->first('name') }}</strong>
                </span>
            @endif
        </div>

        @foreach($langs as $keyCode => $language)
            <div class="form-group">
                {{ Form::label("translate[{$keyCode}]", $language['name']) }}
                {{ Form::textarea("translate[{$keyCode}]", old("translate[{$keyCode}]", empty($translated[$keyCode]) ?: $translated[$keyCode]), ['class' => 'form-control ' . (!$errors->has("translate[{$keyCode}]") ?: 'is-invalid')]) }}
                @if ($errors->has("translate[{$keyCode}]"))
                    <span class="invalid-feedback">
                        <strong>{{ $errors->first("translate[{$keyCode}]") }}</strong>
                    </span>
                @endif
            </div>
        @endforeach

        {{ Form::submit('Обновить', ['class' => 'btn btn-primary']) }}

        {{ Form::close() }}
    </div>
@endsection