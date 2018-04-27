@extends('layouts.advert-edit')

@section('content')
    <h1 class="page-header">
        #{{ $advert->id }} {{ $advert->title }} - {{ t('home.advertPricesHeading') }}
    </h1>

    <hr />

    @parent

    <div>
        {{ Form::open(['method' => 'PUT', 'url' => route('cabinet.advert.update.prices', $advert)]) }}

            @include('advert.partials.prices', [
                'advertPrice' => \App\Helpers\ArrayHelper::multipleDataFormToCorrectArray(old('prices')) ?? $prices,
                'types' => $types,
                'listCategory' => $listCategory
            ])

            <hr />

            {{ Form::submit('Сохранить', ['class' => 'btn btn-raised btn-success']) }}
        {{ Form::close() }}
    </div>
@endsection