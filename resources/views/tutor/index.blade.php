@extends('layouts.profile')

@section('content')
    @parent

    @include('tutor._profile', compact('profile'))
@endsection