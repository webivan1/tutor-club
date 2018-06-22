@extends('layouts.cabinet')

@section('title', t('Create new ad'))

@section('content')
    <h1 class="page-header">
        {{ t('Choose a category') }}
    </h1>

    <hr />

    <div class="list-group">
        @foreach ($category as $item)
            <a class="list-group-item" href="{{ route('cabinet.advert.create.end', $item) }}">
                {{ t($item->name) }}
            </a>
        @endforeach
    </div>
@endsection