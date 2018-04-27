@extends('layouts.cabinet')

@section('title', 'Добавить объявление')
@section('not-drawers', true)

@section('nav-left')
    {{ Html::link(route('cabinet.advert.index'), t('home.listOwnAdverts'), ['class' => 'list-group-item']) }}
@endsection

@section('content')
    <h1 class="page-header">
        Выберите категорию
    </h1>

    <hr />

    <div class="list-group">
        @foreach ($category as $item)
            <a class="list-group-item" href="{{ route('cabinet.advert.create.end', $item) }}">
                {{ $item->name }}
            </a>
        @endforeach
    </div>
@endsection