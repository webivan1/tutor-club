@extends('layouts.advert-edit')

@section('content')
    <h1 class="page-header">
        #{{ $advert->id }} {{ $advert->title }} - {{ t('home.errorSendToModerationAdvert') }}
    </h1>

    <hr />

    @parent

    <div class="alert alert-danger">
        {{ t('home.alertDangerFixesErrorSendToModerationAdvert') }}
    </div>
@endsection