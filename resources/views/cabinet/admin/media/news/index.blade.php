@extends('layouts.admin')

@section('title', '| News key')

@section('content')
    <h1 class="page-header">
        Новости
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
                    {!! $sort->link('heading') !!}
                </th>
                <th>
                    {!! $sort->link('published_at') !!}
                </th>
                <th>
                    {!! $sort->link('status') !!}
                </th>
                <th>Управление</th>
            </tr>
            </thead>
            <tbody>
            @foreach ($models as $item)
                <tr>
                    <td>{{ $item->id }}</td>
                    <td>{{ $item->heading }}</td>
                    <td>{{ $item->published_at }}</td>
                    <td>{{ $item->status }}</td>
                    <td width="200">
                        <a href="{{ route('cabinet.admin.media.news.edit', $item) }}" class="btn btn-sm btn-info pull-left" style="margin-right: 3px;">
                            Edit
                        </a>

                        {!! Form::open([
                            'method' => 'DELETE',
                            'route' => ['cabinet.admin.media.news.destroy', $item],
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

    <a href="{{ route('cabinet.admin.media.news.create') }}" class="btn btn-success">
        Добавить новость
    </a>
@endsection