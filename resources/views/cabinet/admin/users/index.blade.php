@extends('layouts.admin')

@section('title', '| Users')

@section('content')
    <h1>Список пользователей</h1>

    {{ Form::open([
        'method' => 'get',
        'url' => route('cabinet.admin.users.index')
    ]) }}
        <div class="row">
            <div class="col-md-3">
                <div class="form-group">
                    {{ Form::label('id', 'Номер') }}
                    {{ Form::text('id', request('id'), ['class' => 'form-control']) }}
                </div>
            </div>
            <div class="col-md-3">
                <div class="form-group">
                    {{ Form::label('name', 'Имя') }}
                    {{ Form::text('name', request('name'), ['class' => 'form-control']) }}
                </div>
            </div>
            <div class="col-md-3">
                <div class="form-group">
                    {{ Form::label('email', 'Email') }}
                    {{ Form::text('email', request('email'), ['class' => 'form-control']) }}
                </div>
            </div>
            <div class="col-md-3">
                <div class="form-group">
                    {{ Form::label('status', 'Статус') }}
                    {{ Form::select('status', ['' => 'Выбрать'] + $statuses, request('status'), [
                        'class' => 'form-control'
                    ]) }}
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col-md-3">
                <div class="form-group">
                    {{ Form::label('role', 'Роль') }}
                    {{ Form::select('role', ['' => 'Выбрать'] + $roles, request('role'), [
                        'class' => 'form-control'
                    ]) }}
                </div>
            </div>
        </div>
        {{ Form::submit('Найти', ['class' => 'btn btn-success']) }}
        <a href="{{ route('cabinet.admin.users.index') }}" class="btn btn-warning">
            Сбросить
        </a>
    {{ Form::close() }}

    <hr>

    <p>Всего пользователей: {{ $models->total() }}</p>

    <div class="table-responsive">
        <table class="table table-bordered table-striped">
            <thead>
            <tr>
                <th>
                    {!! $sort->link('id') !!}
                </th>
                <th>
                    {!! $sort->link('name') !!}
                </th>
                <th>Email</th>
                <th>Статус</th>
                <th>Роли</th>
                <th>
                    {!! $sort->link('created_at') !!}
                </th>
                <th>
                    {!! $sort->link('updated_at') !!}
                </th>
                <th>Управление</th>
            </tr>
            </thead>
            <tbody>
                @foreach ($models as $user)
                    <tr>
                        <td>{{ $user->id }}</td>
                        <td>{{ $user->name }}</td>
                        <td>{{ $user->email }}</td>
                        <td>
                            <span class="badge badge-{{ $user->statusesColor()[$user->status] }}">
                                {{ $user->statusesLabels()[$user->status] }}
                            </span>
                        </td>
                        <td>{{ $user->roles()->pluck('name')->implode(' ') }}</td>
                        <td>{{ $user->created_at->format('d.m.Y H:i') }}</td>
                        <td>{{ $user->updated_at->format('d.m.Y H:i') }}</td>
                        <td width="200">
                            <a href="{{ route('cabinet.admin.users.edit', $user->id) }}" class="btn btn-sm btn-info pull-left" style="margin-right: 3px;">
                                Edit
                            </a>

                            {!! Form::open([
                                'method' => 'DELETE',
                                'route' => ['cabinet.admin.users.destroy', $user->id],
                                'onsubmit' => 'return confirm("Вы уверены?");'
                            ]) !!}
                            {!! Form::submit('Delete', ['class' => 'btn btn-sm btn-danger']) !!}
                            {!! Form::close() !!}
                        </td>
                    </tr>
                @endforeach
            </tbody>

        </table>
    </div>

    {{ $models->links() }}

    <a href="{{ route('cabinet.admin.users.create') }}" class="btn btn-success">
        Добавить пользователя
    </a>
@endsection