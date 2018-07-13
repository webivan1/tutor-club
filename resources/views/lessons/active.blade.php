<?php

use App\Entity\Advert\AdvertPrice;

?>

@extends('layouts.profile')

@section('title', t('SeoTitlelessonsListActive'))
@section('description', t('SeoDescriptionlessonsListActive'))

@section('content')
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
                            {{ Html::link(route('tutor.view', $item->tutorModel->user->id), $item->tutorModel->user->name) }}
                        </td>
                        <td>
                            @if($item->isActive())
                                {{ Html::link(route('classroom.home', $item), t('In classroom'), [
                                    'class' => 'btn btn-success btn-sm'
                                ]) }}
                            @elseif(\Auth::user()->tutor && $item->hasTutor(\Auth::user()->tutor->id))
                                {{ Html::link('/', t('Edit'), [
                                    'class' => 'btn btn-warning btn-sm'
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