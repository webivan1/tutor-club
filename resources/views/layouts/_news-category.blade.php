@foreach($data as $item)
    <a href="{{ route('media.show', $item['slug']) }}">
        {{ t($item->title) }}
    </a>
@endforeach