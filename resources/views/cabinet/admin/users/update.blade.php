@extends('layouts.admin')

@section('title', '| Edit User')

@section('content')
    <div>
        <h1>Редактировать пользователя #{{ $user->id }}</h1>

        <hr>

        {{ Form::open([
            'method' => 'PUT',
            'url' => route('cabinet.admin.users.update', $user)
        ]) }}

            <div class="form-group">
                {{ Form::label('name', 'Name') }}
                {{ Form::text('name', old('name', $user->name), ['class' => 'form-control']) }}
            </div>

            <div class="form-group">
                {{ Form::label('email', 'Email') }}
                {{ Form::email('email', old('email', $user->email), ['class' => 'form-control']) }}
            </div>

            <div class='form-group'>
                {{ Form::label('role', 'Роль') }}
                {{ Form::select('role', ['' => 'Выбрать'] + $roles, old('role', $user->roleUser ? $user->roleUser->role_id : ''), [
                    'class' => 'form-control'
                ]) }}
            </div>

            <div class='form-group'>
                {{ Form::label('status', 'Статус') }}
                {{ Form::select('status', ['' => 'Выбрать'] + $statuses, old('status', $user->status), [
                    'class' => 'form-control'
                ]) }}
            </div>

            {{ Form::submit('Обновить', ['class' => 'btn btn-primary']) }}

        {{ Form::close() }}
    </div>
@endsection