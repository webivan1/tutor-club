@extends('layouts.admin')

@section('title', '| Languages')

@section('content')
    <h1 class="page-header">
        Языки
    </h1>

    <hr />

    {{ Form::open([
        'method' => 'get',
        'url' => route('cabinet.admin.lang.index')
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
                    {{ Form::label('name', 'Название') }}
                    {{ Form::text('name', request('name'), ['class' => 'form-control']) }}
                </div>
            </div>
            <div class="col-md-3">
                <div class="form-group">
                    {{ Form::label('value', 'Значение') }}
                    {{ Form::text('value', request('value'), ['class' => 'form-control']) }}
                </div>
            </div>
            <div class="col-md-3">
                <div class="form-group">
                    {{ Form::label('native', 'Нативное название') }}
                    {{ Form::text('native', request('native'), ['class' => 'form-control']) }}
                </div>
            </div>
        </div>
        {{ Form::submit('Найти', ['class' => 'btn btn-success']) }}
        <a href="{{ route('cabinet.admin.lang.index') }}" class="btn btn-warning">
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
                        {!! $sort->link('value') !!}
                    </th>
                    <th>
                        {!! $sort->link('name') !!}
                    </th>
                    <th>
                        {!! $sort->link('native') !!}
                    </th>
                    <th>Управление</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($models as $item)
                    <tr>
                        <td>{{ $item->id }}</td>
                        <td>{{ $item->value }}</td>
                        <td>{{ $item->name }}</td>
                        <td>{{ $item->native }}</td>
                        <td width="200">
                            <a href="{{ route('cabinet.admin.lang.edit', $item->id) }}" class="btn btn-sm btn-info pull-left" style="margin-right: 3px;">
                                Edit
                            </a>

                            {!! Form::open([
                                'method' => 'DELETE',
                                'route' => ['cabinet.admin.lang.destroy', $item->id],
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

    <a href="{{ route('cabinet.admin.lang.create') }}" class="btn btn-success">
        Добавить язык
    </a>
@endsection