@extends('layouts.email')

@section('content')
    @parent

    <?php dump($user, $user->getOriginPassword(), $user->verify_token, $user->isWait()) ?>

    {{ t('mail.hello', ['username' => $user->name]) }}
    {{ t('mail.register_info', ['pass' => $user->getOriginPassword()]) }}

    @if ($user->isWait())
        <a href="{{ route('verify', ['token' => $user->verify_token]) }}">
            {{ t('mail.activation_link') }}
        </a>
    @endif
@endsection