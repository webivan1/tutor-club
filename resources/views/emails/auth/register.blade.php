@extends('layouts.email')

@section('content')
    @parent

    <p>Привет Вася!</p>

    <pre><?php var_dump([
        'pass' => $user->getOriginPassword(),
        'state' => $user->isWait(),
        'token' => $user->verify_token
    ]) ?></pre>

    {{ t('mail.hello', ['username' => $user->name]) }}
    {{ t('mail.register_info', ['pass' => $user->getOriginPassword()]) }}

    @if ($user->isWait())
        <a href="{{ route('verify', ['token' => $user->verify_token]) }}">
            {{ t('mail.activation_link') }}
        </a>
    @endif
@endsection