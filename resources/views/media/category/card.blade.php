<div class="card">
    {{ Html::image(Storage::url($newsItem->image->getPreset('100x180')), 'Фото', [
        'class' => 'card-img-top'
    ]) }}

    <div class="card-body">
        @if ($newsItem->published_at)
            <div class="text-right mb-2">
                <small class="text-muted fs-12">
                    <i class="fas fa-calendar-alt"></i> {{ $newsItem->published_at->format('d/m/Y') }}
                </small>
            </div>
        @endif

        <h5 class="card-title">{{ $newsItem->title }}</h5>
        <p class="card-text">{{ substr(strip_tags($newsItem->content), 0, 100) }}</p>


    </div>

    <div class="card-footer text-right">
        <a href="{{ route('media.material.show', $newsItem->slug) }}" class="mdc-button mdc-button--raised">
            {{ t('view news') }}
        </a>
    </div>
</div>