@extends('layouts.admin')

@section('title', '| Edit advert')

@section('content')
    <div>
        <h1>Редактировать {{ $advert->id }}</h1>

        <hr>

        {{ Form::open([
            'method' => 'PUT',
            'url' => route('cabinet.admin.advert.update', $advert)
        ]) }}

            <div>
                {{ Html::link(route('cabinet.advert.update', $advert), 'Посмотреть объявление', [
                    'class' => 'btn btn-link text-danger',
                    'target' => '_blank'
                ]) }}
            </div>

            <div class="form-group">
                {{ Form::label('status', 'Статус') }}
                {{ Form::select('status', $advert->statuses(), old('status', $advert->status), ['class' => 'form-control ' . (!$errors->has('status') ?: 'is-invalid')]) }}
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