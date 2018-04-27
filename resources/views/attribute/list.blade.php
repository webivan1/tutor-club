<div class="table-responsive">
    <table class="table table-bordered table-striped">
        <thead>
            <tr>
                <th>#</th>
                <th>Название</th>
                <th>Тип</th>
                <th>Варианты</th>
                <th>Сортировка</th>
                <th>Обязательное</th>
                <th>Управление</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($models as $item)
                <tr>
                    <td>{{ $item->id }}</td>
                    <td>{{ $item->label }}</td>
                    <td>{{ $item->types()[$item->type] }}</td>
                    <td>{!! str_replace("\n", "<br />", $item->variants) !!}</td>
                    <td>{{ $item->sort }}</td>
                    <td>{{ $item->required ? 'Да' : 'Нет' }}</td>
                    <td width="200">
                        <a target="_blank" href="{{ route('cabinet.admin.category.attribute.edit', [$item->category_id, $item]) }}" class="btn btn-sm btn-info pull-left" style="margin-right: 3px;">
                            Edit
                        </a>

                        {!! Form::open([
                            'method' => 'DELETE',
                            'url' => route('cabinet.admin.category.attribute.destroy', [$item->category_id, $item]),
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