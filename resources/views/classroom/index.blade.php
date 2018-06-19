@extends('layouts.classroom')

@section('script.head')
    <link href="https://cdnjs.cloudflare.com/ajax/libs/summernote/0.8.9/summernote-bs4.css" rel="stylesheet">
@endsection

@section('script.body')
    <script src="https://cdnjs.cloudflare.com/ajax/libs/summernote/0.8.9/summernote-bs4.js"></script>
@endsection

@section('content')
    <h1>
        {{ $room->subject }}
    </h1>

    <div class="card mb-3">
        <div class="card-body">
            <h4 class="my-0">{{ t('Of participants') }} <b id="total-users"></b></h4>
        </div>
    </div>

    <classroom
        user="{{ \Auth::user() }}"
        host="{{ route('home') }}"
        room="{{ json_encode($room) }}"
        trans="{{ json_encode([
            'Send' => t('Send'),
            'MoreMessages' => t('More'),
            'ErrorDoubleConnect' => t('You are already in the room!')
        ]) }}"
    ></classroom>
@endsection