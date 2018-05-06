@extends('layouts.admin')

@section('title', '| Adverts')

@section('content')
    <h1 class="page-header">
        Объявления репетиторов
    </h1>

    <hr />

    {{ Form::open([
        'method' => 'get',
        'url' => route('cabinet.admin.advert.index')
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
                    {{ Form::label('profile_id', 'Профиль') }}
                    {{ Form::text('profile_id', request('profile_id'), ['class' => 'form-control']) }}
                </div>
            </div>
            <div class="col-md-3">
                <div class="form-group">
                    {{ Form::label('user_id', 'Юзер') }}
                    {{ Form::text('user_id', request('user_id'), ['class' => 'form-control']) }}
                </div>
            </div>
            <div class="col-md-3">
                <div class="form-group">
                    {{ Form::label('lang', 'Язык') }}
                    {{ Form::select('lang', ['' => 'Выбрать'] + \App\Helpers\LangHelper::langList(), request('lang'), ['class' => 'form-control']) }}
                </div>
            </div>
        </div>

        <div class="row">
            <div class="col-md-3">
                <div class="form-group">
                    {{ Form::label('price_from', 'Цена от') }}
                    {{ Form::text('price_from', request('price_from'), ['class' => 'form-control']) }}
                </div>
            </div>
            <div class="col-md-3">
                <div class="form-group">
                    {{ Form::label('price_type', 'Валюта') }}
                    {{ Form::select('price_type', ['' => 'Выбрать'] + \App\Entity\Advert\AdvertPrice::types(), request('price_type'), ['class' => 'form-control']) }}
                </div>
            </div>
            <div class="col-md-3">
                <div class="form-group">
                    {{ Form::label('status', 'Статус') }}
                    {{ Form::select('status', ['' => 'Выбрать'] + \App\Entity\Admin\Advert::statuses(), request('status'), ['class' => 'form-control']) }}
                </div>
            </div>
        </div>

        @if ($category)
            <div class="row">
                <div class="col-md-6">
                    <div class="form-group">
                        {{ Form::label('category', 'Категории') }}
                        <select name="category[]" id="category" class="form-control" multiple>
                            <option value="">Выбрать</option>
                            @foreach ($category as $item)
                                <option value="{{ $item->id }}" {{ in_array($item->id, old('category', [])) ? 'selected' : null }}>
                                    {!! $item->depth > 0 ? str_repeat("&nbsp;&nbsp;", $item->depth) : null !!}
                                    {{ $item->name }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                </div>
            </div>
        @endif

        {{ Form::submit('Найти', ['class' => 'btn btn-success']) }}
        <a href="{{ route('cabinet.admin.advert.index') }}" class="btn btn-warning">
            Сбросить
        </a>
    {{ Form::close() }}

    <hr />

    <div class="table-responsive">
        <table class="table table-bordered table-striped">
            <thead>
                <tr>
                    <th>{!! $sort->link('id') !!}</th>
                    <th>Пользователь</th>
                    <th>Профиль</th>
                    <th>Название главной категории</th>
                    <th>Язык</th>
                    <th>Статус</th>
                    <th>Дата добавления</th>
                    <th>Дата обновления</th>
                    <th>Управление</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($models as $item)
                    <tr>
                        <td>{{ $item->id }}</td>
                        <td>
                            {{ Html::link(route('cabinet.admin.users.edit', $item->user_id), $item->user->name) }}
                        </td>
                        <td>
                            {{ Html::link(route('cabinet.admin.tutor.edit', $item->profile_id), $item->profile_id) }}
                        </td>
                        <td>{{ $item->title }}</td>
                        <td>{{ $item->lang }}</td>
                        <td>{{ $item->statuses()[$item->status] }}</td>
                        <td>{{ $item->created_at }}</td>
                        <td>{{ $item->updated_at }}</td>
                        <td width="200">
                            <a href="{{ route('cabinet.admin.advert.edit', $item->id) }}" class="btn btn-sm btn-info pull-left" style="margin-right: 3px;">
                                Edit
                            </a>

                            {!! Form::open([
                                'method' => 'DELETE',
                                'route' => ['cabinet.admin.advert.destroy', $item->id],
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