@extends('layouts.email')

@section('content')
    @parent

    <p>{{ t('mail.hello', ['username' => $user->name]) }}</p>

    @if ($user->isWait())
        <p>
            <a href="{{ route('verify', ['token' => $user->verify_token]) }}">
                {{ t('mail.activation_link') }}
            </a>
        </p>
    @endif
@endsection