@extends('layouts.admin')

@section('title', '| Tutor list')

@section('content')
    <h1 class="page-header">
        Профили репетиторов
    </h1>

    <hr />

    {{ Form::open([
        'method' => 'get',
        'url' => route('cabinet.admin.tutor.index')
    ]) }}
        <div class="row">
            <div class="col-md-3">
                <div class="form-group">
                    {{ Form::label('id', '#') }}
                    {{ Form::text('id', request('id'), ['class' => 'form-control']) }}
                </div>
            </div>
            <div class="col-md-3">
                <div class="form-group">
                    {{ Form::label('user_id', 'User') }}
                    {{ Form::text('user_id', request('user_id'), ['class' => 'form-control']) }}
                </div>
            </div>
            <div class="col-md-3">
                <div class="form-group">
                    {{ Form::label('country_code', 'Country code') }}
                    {{ Form::text('country_code', request('country_code'), ['class' => 'form-control']) }}
                </div>
            </div>
            <div class="col-md-3">
                <div class="form-group">
                    {{ Form::label('phone', 'Phone') }}
                    {{ Form::text('phone', request('phone'), ['class' => 'form-control']) }}
                </div>
            </div>
            <div class="col-md-3">
                <div class="form-group">
                    {{ Form::label('phone_verified', 'Phone verified') }}
                    {{ Form::select('phone_verified', [
                        '' => 'Select',
                        1 => 'Yes',
                        0 => 'No'
                    ], request('phone_verified'), ['class' => 'form-control']) }}
                </div>
            </div>
            <div class="col-md-3">
                <div class="form-group">
                    {{ Form::label('status', 'Status') }}
                    {{ Form::select('status', ['' => 'Select'] + $model->statuses(), request('status'), ['class' => 'form-control']) }}
                </div>
            </div>
        </div>
        {{ Form::submit('Найти', ['class' => 'btn btn-success']) }}
        <a href="{{ route('cabinet.admin.tutor.index') }}" class="btn btn-warning">
            Сбросить
        </a>
    {{ Form::close() }}

    <hr />

    <div class="table-responsive">
        <table class="table table-bordered table-striped">
            <thead>
                <tr>
                    <th>{!! $sort->link('id') !!}</th>
                    <th>User</th>
                    <th>Status</th>
                    <th>Phone</th>
                    <th>Управление</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($models as $item)
                    <tr>
                        <td>{{ $item->id }}</td>
                        <td>#{{ $item->user->id }} - {{ $item->user->name }}</td>
                        <td>{{ $item->statuses()[$item->status] }}</td>
                        <td>
                            {{ $item->country_code }} {{ $item->phone }}
                            {{ $item->phone_verified ? 'Проверен' : '' }}
                        </td>
                        <td width="200">
                            <a href="{{ route('cabinet.admin.tutor.edit', $item->id) }}" class="btn btn-sm btn-info pull-left" style="margin-right: 3px;">
                                Edit
                            </a>

                            {!! Form::open([
                                'method' => 'DELETE',
                                'route' => ['cabinet.admin.tutor.destroy', $item->id],
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
@endsection