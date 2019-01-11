<?php

use App\Entity\Classroom\Classroom;

/** @var Classroom $room */

?>

@extends('layouts.classroom')

@section('script.head')
    <link href="https://cdnjs.cloudflare.com/ajax/libs/summernote/0.8.9/summernote-bs4.css" rel="stylesheet">
@endsection

@section('script.body')
    <script src="https://cdnjs.cloudflare.com/ajax/libs/summernote/0.8.9/summernote-bs4.js"></script>
@endsection

@section('content')

    <?php ob_start() ?>

    @if ((int)$room->tutor_id === Auth::user()->tutorId() && $room->isActive())
        <div class="card mb-3">
            <div class="card-body">
                <a onclick="return confirm('{{ t('Are tou sure') }}')" href="{{ route('classroom.home.close', $room) }}" class="bg-green mdc-button mdc-button--raised">
                    {{ t('Lesson is completed') }}
                </a>
            </div>
        </div>
    @endif

    <?php $content = ob_get_contents() ?>
    <?php ob_get_clean() ?>

    <classroom
        heading="{{ $room->subject }}"
        user="{{ Auth::user() }}"
        host="{{ route('home') }}"
        room="{{ json_encode($room) }}"
        trans="{{ json_encode([
            'Send' => t('Send'),
            'MoreMessages' => t('More'),
            'ErrorDoubleConnect' => t('You are already in the room!'),
            'OfParticipants' => t('Of participants')
        ]) }}"
        close-classroom-html="{{ $content }}"
    ></classroom>
@endsection