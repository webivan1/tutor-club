@extends('layouts.advert-edit')

@section('script.head')
    <script src="//cdn.ckeditor.com/4.8.0/basic/ckeditor.js"></script>
@endsection

@section('content')
    <h1 class="page-header">
        #{{ $advert->id }} {{ $advert->title }}
    </h1>

    <hr />

    @parent

    <div>
        {{ Form::open(['method' => 'PUT', 'url' => route('cabinet.advert.update', $advert)]) }}

            @include('advert.partials.info-fields', compact('advert'))

            <hr />

            {{ Form::submit('Сохранить', ['class' => 'btn btn-raised btn-success']) }}
        {{ Form::close() }}
    </div>
@endsection