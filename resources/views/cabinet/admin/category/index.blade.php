@extends('layouts.admin')

@section('title', '| Category')

@section('content')
    <h1 class="page-header">
        Категории
    </h1>

    @include('cabinet.admin.category._list', compact('models'))

    <a href="{{ route('cabinet.admin.category.create') }}" class="btn btn-success">
        Добавить категорию
    </a>
@endsection