<ul class="nav flex-column">
    @foreach($data as $item)
        <li class="nav-item mb-1">
            <a class="nav-link px-0 py-0 text-capitalize" href="{{ route('media.show', $item) }}">
                {{ t($item->title) }}
            </a>
        </li>
    @endforeach
</ul>