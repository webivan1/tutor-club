<?php

use App\Entity\Advert\AdvertPrice;

?>

@extends('layouts.profile')

@section('title', $item->subject)
@section('description', $item->subject)

@section('content')
    <h1 class="mb-4">{{ $item->subject }}</h1>

    @if ($item->users)
        <div class="table-responsive app-vue">
            <table class="table">
                <thead>
                    <tr>
                        <th>{{ t('Avatar') }}</th>
                        <th>ID</th>
                        <th>{{ t('Username') }}</th>
                        <th>{{ t('email') }}</th>
                        <th>{{ t('Chat') }}</th>
                        <th>{{ t('Status') }}</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($item->users as $user)
                        <tr>
                            <td>
                                @if ($user->user->image)
                                    <img width="80" src="{{ $user->user->image->getPreset('150x150') }}" />
                                @else
                                    -
                                @endif
                            </td>
                            <td>
                                {{ $user->user->id }} <online :user="<?= $user->user->id ?>"></online>
                            </td>
                            <td>
                                {{ $user->user->name }}
                            </td>
                            <td>
                                {{ $user->user->email }}
                            </td>
                            <td>
                                <add-dialog
                                    :user="{{ $user->user->id }}"
                                    title="{{ $user->user->name }}"
                                    :data-json="{}"
                                ></add-dialog>
                            </td>
                            <td>
                                @if ($user->isActive())
                                    <span class="badge badge-success fs-14">
                                        {{ t('Accepted the invitation') }}
                                    </span>
                                @else
                                    <span class="badge badge-secondary fs-14">
                                        {{ t('The user has not yet accepted the invitation') }}
                                    </span>
                                @endif
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    @endif

    <div class="row app-vue">
        <div class="col-md-6">
            <div class="card mb-2">
                <table class="table my-0">
                    <tbody>
                        <tr>
                            <td>{{ t('Date start') }}</td>
                            <td>
                                <real-timer date="{{ $item->started_at }}"></real-timer>
                            </td>
                        </tr>
                        <tr>
                            <td>{{ t('Price') }}</td>
                            <td>
                                {{ AdvertPrice::types()[$item->price_type] ?? '' }}
                                <b>{{ $item->price }}</b> / {{ $item->minutes }} {{ t('Minutes') }}
                            </td>
                        </tr>
                        <tr>
                            <td>{{ t('Webcam') }}</td>
                            <td>
                                @if ($item->video)
                                    <i class="fas fa-check text-green fs-24"></i>
                                @else
                                    <i class="fas fa-ban text-danger fs-24"></i>
                                @endif
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>

            {{ Form::open(['url' => route('profile.lesson.close.active', $item), 'method' => 'POST']) }}

                <div class="form-group">
                    <label>{{ t('Why you want close that lesson?') }}<span class="text-danger">*</span></label>
                    {{ Form::textarea('comment', old('comment'), [
                        'class' => 'form-control ' . (!$errors->has('comment') ?: 'is-invalid')
                    ]) }}
                    @if ($errors->has('comment'))
                        <span class="invalid-feedback">
                            <strong>{{ $errors->first('comment') }}</strong>
                        </span>
                    @endif
                </div>

                {{ Form::submit(t('Delete lesson'), [
                    'class' => 'btn btn-danger',
                    'onclick' => 'return confirm("Are you sure?");'
                ]) }}

            {{ Form::close() }}
        </div>
    </div>
@endsection



