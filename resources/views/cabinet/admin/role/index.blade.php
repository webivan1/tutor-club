@extends('layouts.admin')

@section('title', '| Role')

@section('content')
    <h1 class="page-header">
        Список ролей
    </h1>

    <hr />

    {{ Form::open([
        'method' => 'get',
        'url' => route('cabinet.admin.role.index')
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
                    {{ Form::label('title', 'Название роли') }}
                    {{ Form::text('title', request('title'), ['class' => 'form-control']) }}
                </div>
            </div>
            <div class="col-md-3">
                <div class="form-group">
                    {{ Form::label('name', 'Роль') }}
                    {{ Form::text('name', request('name'), ['class' => 'form-control']) }}
                </div>
            </div>
            <div class="col-md-3">
                <div class="form-group">
                    {{ Form::label('permission', 'Разрешение') }}
                    {{ Form::select('permission', $permissions, request('permission'), [
                        'class' => 'form-control'
                    ]) }}
                </div>
            </div>
        </div>
        {{ Form::submit('Найти', ['class' => 'btn btn-success']) }}
        <a href="{{ route('cabinet.admin.role.index') }}" class="btn btn-warning">
            Сбросить
        </a>
    {{ Form::close() }}

    <hr />

    <div class="table-responsive">
        <table class="table table-bordered table-striped">
            <thead>
            <tr>
                <th>
                    {!! $sort->link('id') !!}
                </th>
                <th>
                    {!! $sort->link('level') !!}
                </th>
                <th>
                    {!! $sort->link('title') !!}
                </th>
                <th>
                    {!! $sort->link('name') !!}
                </th>
                <th>Управление</th>
            </tr>
            </thead>
            <tbody>
            @foreach ($models as $role)
                <tr>
                    <td>{{ $role->id }}</td>
                    <td>{{ $role->level }}</td>
                    <td>{{ $role->title }}</td>
                    <td>{{ $role->name }}</td>
                    <td width="200">
                        <a href="{{ route('cabinet.admin.role.edit', $role->id) }}" class="btn btn-sm btn-info pull-left" style="margin-right: 3px;">
                            Edit
                        </a>

                        {!! Form::open([
                            'method' => 'DELETE',
                            'route' => ['cabinet.admin.role.destroy', $role->id],
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

    <a href="{{ route('cabinet.admin.role.create') }}" class="btn btn-success">
        Добавить роль
    </a>
@endsection