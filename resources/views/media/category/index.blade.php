@extends('layouts.app')

@section('h1', t($category->heading))

@section('content')
    @if ($news->total() > 0)
        <div class="mb-5">
            @foreach (array_chunk($news->items(), 3) as $newsGroup)
                <div class="card-deck mb-4">
                    @foreach ($newsGroup as $newsItem)
                        @include('media.category.card', compact('$newsItem'))
                    @endforeach
                </div>
            @endforeach
        </div>

        {{ $news->links() }}
    @else
        <div class="alert alert-info">
            {{ t('No content yet') }}
        </div>
    @endif

    <div class="pt-3">
        {{ t($category->content) }}
    </div>
@endsection
