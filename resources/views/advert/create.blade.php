<?php

use App\Entity\Advert\AdvertPrice;
use App\Helpers\ArrayHelper;

$advertPrice = ArrayHelper::multipleDataFormToCorrectArray(old('prices', []));
$advertPrice = empty($advertPrice) ? [new AdvertPrice()] : $advertPrice;

?>

@extends('layouts.cabinet')

@section('not-drawers', true)

@section('script.head')
    <script src="//cdn.ckeditor.com/4.8.0/basic/ckeditor.js"></script>
@endsection

@section('content')
    <h1 class="page-header">
        {{ t($category->name) }}
    </h1>

    <hr />

    <div>
        {{ Form::open(['method' => 'POST', 'url' => route('cabinet.advert.create.end', $category)]) }}

            @include('advert.partials.info-fields')
            @include('advert.partials.prices', compact('advertPrice', 'listCategory', 'types'))

            <hr />

            {{ Form::submit(t('Save and continue'), ['class' => 'btn btn-raised btn-success']) }}
        {{ Form::close() }}
    </div>
@endsection