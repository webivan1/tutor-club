<?php

use App\Entity\Advert\AdvertPrice;

?>

@extends('layouts.profile')

@section('title', t('SeoTitleLessonsListActive'))
@section('description', t('SeoDescriptionLessonsListActive'))

@section('content')

    <h1 class="mb-3">{{ t('SeoHeadingLessonsListActive') }}</h1>

    @include('lessons._nav')

    @if($models->total())
        <table class="table app-vue">
            <thead>
                <tr>
                    <th>{!! $sort->link('id') !!}</th>
                    <th>{{ t('Lesson theme') }}</th>
                    <th>{!! $sort->link('started_at') !!}</th>
                    <th>{{ t('Price') }}</th>
                    <th>{{ t('Tutor name') }}</th>
                    <th></th>
                </tr>
            </thead>
            <tbody>
                @foreach($models->items() as $item)
                    <tr>
                        <td>{{ $item->id }}</td>
                        <td>{{ $item->subject }}</td>
                        <td>
                            <real-timer date="{{ $item->started_at }}"></real-timer>
                        </td>
                        <td>
                            {{ AdvertPrice::types()[$item->price_type] ?? '' }}
                            <b>{{ $item->price }}</b> / {{ $item->minutes }} {{ t('Minutes') }}
                        </td>
                        <td>
                            @if ($item->tutorModel)
                                {{ Html::link(route('tutor.view', $item->tutorModel->user->id), $item->tutorModel->user->name) }}
                            @else
                                -
                            @endif
                        </td>
                        <td>
                            @if($item->isActive())
                                {{ Html::link(route('classroom.home', $item), t('In classroom'), [
                                    'class' => 'btn btn-success btn-sm'
                                ]) }}
                            @endif

                            @if(\Auth::user()->tutor && $item->hasTutor(\Auth::user()->tutor->id) && !$item->isActive())
                                {{ Html::link(route('profile.lesson.edit.active', $item), t('Edit'), [
                                    'class' => 'btn btn-warning btn-sm'
                                ]) }}
                            @elseif(!$item->isActive() && $item->canClose())
                                {{ Html::link(route('profile.lesson.remove', $item), t('Exit'), [
                                    'class' => 'btn btn-danger btn-sm',
                                    'onclick' => 'return confirm("Are you sure?");'
                                ]) }}
                            @endif
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>

        {!! $models->links() !!}
    @else
        <div class="alert alert-secondary">
            {{ t('Lesson list is empty') }}
        </div>
    @endif
@endsection