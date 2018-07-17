@extends('layouts.app')

@section('h1', t($category->heading))

@section('content')
    <div class="card-deck mt-3 mb-2">
        @foreach ($news as $newsItem)
            @include('media.category.card', compact('$newsItem'))
        @endforeach
    </div>

    @if ($news->total > 0)

    @else
        <div class="alert alert-info">

        </div>
    @endif

    {{ $news->links() }}

    <div class="pt-3">
        {{ t($category->content) }}
    </div>
@endsection
