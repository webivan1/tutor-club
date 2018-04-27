<div class="table-responsive">
    <table class="table table-bordered table-striped">
        <thead>
            <tr>
                <th>#</th>
                <th>Название</th>
                <th>Алиас</th>
                <th>Вложенность</th>
                <th>Дочерних категорий</th>
                <th>Сортировка</th>
                <th>Управление</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($models as $item)
                <tr>
                    <td>{{ $item->id }}</td>
                    <td>
                        {!! Html::link(route('cabinet.admin.category.show', $item), $item->name) !!}
                    </td>
                    <td>{{ $item->slug }}</td>
                    <td>{!! $item->depth === 0 ? Html::tag('span', 'root', ['class' => 'badge badge-success']) : $item->depth !!}</td>
                    <td>{{ $item->children()->count() }}</td>
                    <td>
                        <div class="btn-group">
                            {{ Html::link(route('cabinet.admin.category.first', $item), 'First', [
                                'class' => 'btn btn-sm btn-outline-secondary'
                            ]) }}
                            {{ Html::link(route('cabinet.admin.category.up', $item), 'Up', [
                                'class' => 'btn btn-sm btn-outline-secondary'
                            ]) }}
                            {{ Html::link(route('cabinet.admin.category.down', $item), 'Down', [
                                'class' => 'btn btn-sm btn-outline-secondary'
                            ]) }}
                            {{ Html::link(route('cabinet.admin.category.last', $item), 'Last', [
                                'class' => 'btn btn-sm btn-outline-secondary'
                            ]) }}
                        </div>
                    </td>
                    <td width="200">
                        <a href="{{ route('cabinet.admin.category.edit', $item->id) }}" class="btn btn-sm btn-info pull-left" style="margin-right: 3px;">
                            Edit
                        </a>

                        {!! Form::open([
                            'method' => 'DELETE',
                            'route' => ['cabinet.admin.category.destroy', $item->id],
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