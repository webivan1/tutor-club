@extends('layouts.email')

@section('content')
    @parent

    <a href="{{ route('login.provider.email.verify', [
        'user' => $user->id,
        'code' => $user->key_code
    ]) }}">
        {{ t('Follow the link to confirm your email') }}
    </a>
@endsection