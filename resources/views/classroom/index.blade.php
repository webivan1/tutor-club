@extends('layouts.classroom')

@section('left-content')
    <div class="app-vue">
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
    </div>
@endsection

@section('title', '')
@section('description', '')
@section('width-content', 'col-md-12')

@section('content')
    <div class="card">
        <div class="card-body">
            <h1>
                Test heading
            </h1>
        </div>
    </div>

    <h4>{{ t('Of participants') }} <b id="total-users"></b></h4>


@endsection