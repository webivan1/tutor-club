@extends('layouts.admin')

@section('title', '| Category ' . $category->name)

@section('content')
    <h1 class="page-header">
        Категория {{ $category->name }}
    </h1>

    <hr />

    <div class="mb-4 clearfix">
        {!! Html::link(route('cabinet.admin.category.edit', $category), 'Редактировать', [
            'class' => 'btn btn-info'
        ]) !!}
        {!! Html::link(route('cabinet.admin.category.create', ['id' => $category]), 'Добавить дочерних', [
            'class' => 'btn btn-success'
        ]) !!}
        {!! Html::link(route('cabinet.admin.category.attribute.create', $category), 'Добавить атрибут', [
            'class' => 'btn btn-success'
        ]) !!}
        {!! Html::link(route('cabinet.admin.category.destroy', $category), 'Удалить', [
            'class' => 'btn btn-danger pull-right',
            'onclick' => 'return confirm("Вы уверены?");'
        ]) !!}
    </div>

    @include('cabinet.admin.category._face', compact('category'))

    <nav>
        <div class="nav nav-tabs" id="nav-tab" role="tablist">
            <a class="nav-item nav-link active" id="nav-home-tab" data-toggle="tab" href="#nav-home" role="tab" aria-controls="nav-home" aria-selected="true">Дочерние категории</a>
            <a class="nav-item nav-link" id="nav-profile-tab" data-toggle="tab" href="#nav-profile" role="tab" aria-controls="nav-profile" aria-selected="false">Атрибуты</a>
        </div>
    </nav>
    <div class="tab-content" id="nav-tabContent">
        <div class="tab-pane fade show active" id="nav-home" role="tabpanel" aria-labelledby="nav-home-tab">
            @include('cabinet.admin.category._list', [
                'models' => $category->children()->defaultOrder()->withDepth()->get()
            ])
        </div>
        <div class="tab-pane fade" id="nav-profile" role="tabpanel" aria-labelledby="nav-profile-tab">
            @include('attribute.list', [
                'models' => $category->allAttributes(),
                'category' => $category
            ])
        </div>
    </div>
@endsection