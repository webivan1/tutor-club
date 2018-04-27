@extends('layouts.admin')

@section('title', '| Languages key')

@section('content')
    <h1 class="page-header">
        {{ Html::link(route('cabinet.admin.translate.generate'), 'Сгенерировать переводчик', [
            'class' => 'btn btn-primary pull-right'
        ]) }}
        Переводы
    </h1>

    <hr />

    {{ Form::open([
        'method' => 'get',
        'url' => route('cabinet.admin.translate.index')
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
                    {{ Form::label('name', 'Ключ') }}
                    {{ Form::text('name', request('name'), ['class' => 'form-control']) }}
                </div>
            </div>
            <div class="col-md-3">
                <div class="form-group">
                    {{ Form::label('translate', 'Часть текста') }}
                    {{ Form::text('translate', request('translate'), ['class' => 'form-control']) }}
                </div>
            </div>
            <div class="col-md-3">
                <div class="form-group">
                    {{ Form::label('language', 'Язык') }}
                    {{ Form::select('language',
                        ['' => 'Выбрать'] +
                        array_combine(
                            LaravelLocalization::getSupportedLanguagesKeys(),
                            LaravelLocalization::getSupportedLanguagesKeys()
                        ),
                        request('language'),
                        ['class' => 'form-control']
                    ) }}
                </div>
            </div>
        </div>
        {{ Form::submit('Найти', ['class' => 'btn btn-success']) }}
        <a href="{{ route('cabinet.admin.translate.index') }}" class="btn btn-warning">
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
                        {!! $sort->link('name') !!}
                    </th>
                    <th>
                        Перевод
                    </th>
                    <th>Управление</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($models as $item)
                    <tr>
                        <td>{{ $item->id }}</td>
                        <td>{{ $item->name }}</td>
                        <td>
                            {{ !$item->word ? null : $item->word->translate }}
                        </td>
                        <td width="200">
                            <a href="{{ route('cabinet.admin.translate.edit', $item) }}" class="btn btn-sm btn-info pull-left" style="margin-right: 3px;">
                                Edit
                            </a>

                            {!! Form::open([
                                'method' => 'DELETE',
                                'route' => ['cabinet.admin.translate.destroy', $item],
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

    <a href="{{ route('cabinet.admin.translate.create') }}" class="btn btn-success">
        Добавить перевод
    </a>
@endsection