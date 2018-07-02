@extends('layouts.app')

@section('h1', t($category->heading))

@section('content')
    <div class="card">
        <div class="card-body">
            <div class="card-text">
                {{ t($category->content) }}
            </div>
        </div>
    </div>

    <div class="card-deck mt-3 mb-2">
        @foreach ($news as $newsItem)
            @include('media.category.card', compact('$newsItem'))
        @endforeach
    </div>

    {{ $news->links() }}
@endsection
