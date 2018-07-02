<div class="card">
    {{ Html::image(Storage::url($newsItem->image->getPreset('100x180')), 'Фото', [
        'class' => 'card-img-top'
    ]) }}

    <div class="card-body">
        <h5 class="card-title">{{ $newsItem->title }}</h5>
        <p class="card-text">{{ substr(strip_tags($newsItem->content), 0, 100) }}</p>

        @if ($newsItem->published_at)
            <p class="card-text">{{ date('d.m.Y', strtotime($newsItem->published_at)) }}</p>
        @endif
    </div>

    <div class="card-footer">
        <small class="text-muted">
            <a href="{{ route('media.material.show', $newsItem->slug) }}" class="">
                {{ t('view news') }}
            </a>
        </small>
    </div>
</div>