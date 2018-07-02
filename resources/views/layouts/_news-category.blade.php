@foreach($data as $item)
    <a href="{{ route('media.show', $item) }}">
        {{ t($item->title) }}
    </a>
@endforeach