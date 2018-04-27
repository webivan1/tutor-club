@extends('layouts.admin')

@section('title', '| Add translate')

@section('content')
    <div>
        <h1>Добавить перевод</h1>

        <hr>

        {{ Form::open([
            'method' => 'POST',
            'url' => route('cabinet.admin.translate.store')
        ]) }}

        <div class="form-group">
            {{ Form::label('name', 'Ключ') }}
            {{ Form::text('name', old('name'), ['class' => 'form-control ' . (!$errors->has('name') ?: 'is-invalid')]) }}
            @if ($errors->has('name'))
                <span class="invalid-feedback">
                    <strong>{{ $errors->first('name') }}</strong>
                </span>
            @endif
        </div>

        @foreach($languages as $keyCode => $language)
            <div class="form-group">
                {{ Form::label("translate[{$keyCode}]", $language['name']) }}
                {{ Form::textarea("translate[{$keyCode}]", old("translate[{$keyCode}]"), ['class' => 'form-control ' . (!$errors->has("translate[{$keyCode}]") ?: 'is-invalid')]) }}
                @if ($errors->has("translate[{$keyCode}]"))
                    <span class="invalid-feedback">
                        <strong>{{ $errors->first("translate[{$keyCode}]") }}</strong>
                    </span>
                @endif
            </div>
        @endforeach

        {{ Form::submit('Добавить', ['class' => 'btn btn-primary']) }}

        {{ Form::close() }}
    </div>
@endsection