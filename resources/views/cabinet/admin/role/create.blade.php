@extends('layouts.admin')

@section('title', '| Add role')

@section('content')
    <div>
        <h1>Добавить роль</h1>

        <hr>

        {{ Form::open([
            'method' => 'POST',
            'url' => route('cabinet.admin.role.store')
        ]) }}

        <div class="form-group">
            {{ Form::label('title', 'Название роли') }}
            {{ Form::text('title', old('title'), ['class' => 'form-control ' . (!$errors->has('title') ?: 'is-invalid')]) }}
            @if ($errors->has('title'))
                <span class="invalid-feedback">
                    <strong>{{ $errors->first('title') }}</strong>
                </span>
            @endif
        </div>

        <div class="form-group">
            {{ Form::label('name', 'Роль') }}
            {{ Form::text('name', old('name'), ['class' => 'form-control ' . (!$errors->has('name') ?: 'is-invalid')]) }}
            @if ($errors->has('name'))
                <span class="invalid-feedback">
                    <strong>{{ $errors->first('name') }}</strong>
                </span>
            @endif
        </div>

        <div class="form-group">
            {{ Form::label('level', 'Уровень доступа') }}
            {{ Form::text('level', old('level'), ['class' => 'form-control ' . (!$errors->has('level') ?: 'is-invalid')]) }}
            @if ($errors->has('level'))
                <span class="invalid-feedback">
                    <strong>{{ $errors->first('level') }}</strong>
                </span>
            @endif
        </div>

        <div class="form-group">
            <table class="table">
                <thead>
                    <tr>
                        <th colspan="2">
                            <a
                                href="javascript:void(0)"
                                onclick="$(this).closest('table').find('tbody input').prop('checked', true)"
                            >Выбрать все</a> /

                            <a
                                href="javascript:void(0)"
                                onclick="$(this).closest('table').find('tbody input').prop('checked', false)"
                            >Отметить все</a>
                        </th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($permissions as $key => $permission)
                        <tr>
                            <td>
                                {{ Form::label('permissions[' . $key . ']', $permission->title) }}
                            </td>
                            <td>
                                {{ Form::checkbox('permissions[' . $key . ']', $permission->id) }}
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>

        {{ Form::submit('Добавить', ['class' => 'btn btn-primary']) }}

        {{ Form::close() }}
    </div>
@endsection