@extends('layouts.admin')

@section('title', '| Add User')

@section('content')
    <div>
        <h1>Добавить пользователя</h1>

        <hr>

        {{ Form::open([
            'method' => 'POST',
            'url' => route('cabinet.admin.users.store')
        ]) }}

            <div class="form-group">
                {{ Form::label('name', 'Name') }}
                {{ Form::text('name', old('name'), ['class' => 'form-control']) }}
            </div>

            <div class="form-group">
                {{ Form::label('email', 'Email') }}
                {{ Form::email('email', old('email'), ['class' => 'form-control']) }}
            </div>

            <div class='form-group'>
                {{ Form::label('role', 'Роль') }}
                {{ Form::select('role', ['' => 'Выбрать'] + $roles, old('role'), [
                    'class' => 'form-control'
                ]) }}
            </div>

            <div class='form-group'>
                {{ Form::label('status', 'Статус') }}
                {{ Form::select('status', ['' => 'Выбрать'] + $statuses, old('status'), [
                    'class' => 'form-control'
                ]) }}
            </div>

            {{ Form::submit('Add', ['class' => 'btn btn-primary']) }}

        {{ Form::close() }}
    </div>
@endsection