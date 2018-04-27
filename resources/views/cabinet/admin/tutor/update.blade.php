@extends('layouts.admin')

@section('title', '| Edit tutor profile')

@section('content')
    <div>
        <h1>Редактировать #{{ $tutor->id }}</h1>

        <hr>

        {{-- Веременная мера --}}
        @include('tutor._profile', ['profile' => $tutor])

        {{ Form::open([
            'method' => 'PUT',
            'url' => route('cabinet.admin.tutor.update', $tutor)
        ]) }}

            <div class="form-group">
                {{ Form::label('status', 'Status') }}
                {{ Form::select('status', $tutor->statuses(), old('status', $tutor->status), ['class' => 'form-control ' . (!$errors->has('status') ?: 'is-invalid')]) }}
                @if ($errors->has('status'))
                    <span class="invalid-feedback">
                        <strong>{{ $errors->first('status') }}</strong>
                    </span>
                @endif
            </div>

            <div class="form-group">
                {{ Form::label('comment', 'Comment') }}
                {{ Form::textarea('comment', old('comment', $tutor->comment), ['class' => 'form-control ' . (!$errors->has('comment') ?: 'is-invalid')]) }}
                @if ($errors->has('comment'))
                    <span class="invalid-feedback">
                        <strong>{{ $errors->first('comment') }}</strong>
                    </span>
                @endif
            </div>

            {{ Form::submit('Обновить', ['class' => 'btn btn-primary']) }}

        {{ Form::close() }}
    </div>
@endsection