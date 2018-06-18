@extends('layouts.admin')

@section('title', '| News category key')

@section('content')
    <h1 class="page-header">
        Категории новостей
    </h1>

    <hr />

    <div class="table-responsive">
        <table class="table table-bordered table-striped">
            <thead>
            <tr>
                <th>
                    {!! $sort->link('id') !!}
                </th>
                <th>
                    {!! $sort->link('title') !!}
                </th>
                <th>Управление</th>
            </tr>
            </thead>
            <tbody>
            @foreach ($models as $item)
                <tr>
                    <td>{{ $item->id }}</td>
                    <td>{{ $item->title }}</td>
                    <td width="200">
                        <a href="{{ route('cabinet.admin.media.category.edit', $item) }}" class="btn btn-sm btn-info pull-left" style="margin-right: 3px;">
                            Edit
                        </a>

                        {!! Form::open([
                            'method' => 'DELETE',
                            'route' => ['cabinet.admin.media.category.destroy', $item],
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

    <a href="{{ route('cabinet.admin.media.category.create') }}" class="btn btn-success">
        Добавить категорию
    </a>
@endsection