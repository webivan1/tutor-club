<?php

use App\Entity\Advert\AdvertPrice;

?>

@extends('layouts.profile')

@section('title', t('SeoTitlelessonsListActive'))
@section('description', t('SeoDescriptionlessonsListActive'))

@section('content')
    @include('lessons._nav')

    @if($models->total())
        <table class="table">
            <thead>
                <tr>
                    <th>{!! $sort->link('id') !!}</th>
                    <th>{{ t('Lesson theme') }}</th>
                    <th>{!! $sort->link('started_at') !!}</th>
                    <th>{{ t('Price') }}</th>
                    <th>{{ t('Tutor name') }}</th>
                </tr>
            </thead>
            <tbody>
                @foreach($models->items() as $item)
                    <tr>
                        <td>{{ $item->id }}</td>
                        <td>{{ $item->subject }}</td>
                        <td>{{ $item->started_at->format('d/m/Y H:i') }}</td>
                        <td>
                            {{ AdvertPrice::types()[$item->price_type] ?? '' }}
                            <b>{{ $item->price }}</b> / {{ $item->minutes }} {{ t('Minutes') }}
                        </td>
                        <td>
                            {{ Html::link(route('tutor.view', $item->tutorModel->user->id), $item->tutorModel->user->name) }}
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