@extends('layouts.app')

@section('title', t('Tutor :name is page', ['name' => $tutorProfile->user->name]))
@section('description', t('Description tutor :name is public page', ['name' => $tutorProfile->user->name]))
@section('h1', $tutorProfile->user->name)

@section('content')
    <div class="row">
        <div class="col-auto">
            @if ($tutorProfile->user->image)
                <div class="card mb-2">
                    <img width="300" src="{{ $tutorProfile->user->image->getPreset('300x300') }}">
                </div>
            @endif
            <div class="card bg-grey-100">
                <div class="list-group">
                    @if (\Auth::check() && \Auth::id() === $tutorProfile->user->id)
                        <div class="list-group-item">
                            <a href="{{ route('profile.tutor.home') }}" class="mdc-button mdc-button--raised w-100">
                                {{ t('Edit is profile') }}
                            </a>
                        </div>
                    @else
                        <div class="list-group-item text-right">
                            <add-dialog
                                :user="{{ $tutorProfile->user->id }}"
                                title="{{ $tutorProfile->user->name }}"
                                :data-json="{}"
                            ></add-dialog>
                        </div>
                    @endif
                    <div class="list-group-item">
                        {{ t('Rating') }} - 4.8 / 5
                    </div>
                    <div class="list-group-item">
                        {{ t('Comments') }} - 356 UP | 3 DOWN
                    </div>
                </div>
            </div>
        </div>
        <div class="col">
            
        </div>
    </div>
@endsection