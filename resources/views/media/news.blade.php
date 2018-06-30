@extends('layouts.app')

@section('h1', t($news->heading))

@section('content')
    <div class="card">
        <div class="card-body">

            @if ($news->published_at)
                <p class="card-text">{{ date('d.m.Y', strtotime($news->published_at)) }}</p>
            @endif

            {{ Html::image(Storage::url($news->image->getPreset('100x180')), 'Фото', [
                'class' => 'rounded float-left'
            ]) }}

            {{ $news->content }}

        </div>
    </div>
@endsection