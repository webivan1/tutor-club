@extends('layouts.app')

@section('script.body')
    <link href="https://cdnjs.cloudflare.com/ajax/libs/summernote/0.8.9/summernote-bs4.css" rel="stylesheet">
    <script src="https://cdnjs.cloudflare.com/ajax/libs/summernote/0.8.9/summernote-bs4.js"></script>
@endsection

@section('content')
    <div class="card">
        <div class="card-body">
            <h1>
                Home page
            </h1>

            <div class="py-3">
                Hello!
            </div>
        </div>
    </div>

    <classroom
        user="{{ \Auth::user() }}"
        host="{{ route('home') }}"
        room="{{ json_encode($room) }}"
        trans="{{ json_encode([
            'Send' => t('Send'),
            'MoreMessages' => t('More')
        ]) }}"
    ></classroom>
@endsection