@extends('layouts.email')

@section('content')
    @parent

    <p>Статус вашего профиля изменился на {{ $profile->statuses()[$profile->status] }}</p>
@endsection