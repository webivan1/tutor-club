@extends('layouts.app')

@section('title', t($category->title))
@section('description', t($category->description))
@section('width-content', 'col-md-12')
@section('h1', t($category->name))

@section('content')
    @if(!empty($childCategories))
        <div class="row mb-5">
            @foreach (chunk_column($childCategories, 3) as $categoryGroup)
                <div class="col-md-4">
                    <div class="card bg-primary">
                        <div class="list-group">
                            @foreach ($categoryGroup as $item)
                                <a class="list-group-item text-white" href="{{ route('category.show', $item['slug']) }}">
                                    {{ t($item['name']) }}
                                    <small>
                                        {{ $item['total_adverts'] > 0 ? $item['total_adverts'] : '' }}
                                    </small>
                                </a>
                            @endforeach
                        </div>
                    </div>
                </div>
            @endforeach
        </div>
    @endif

    {{-- Vue component rendering --}}
    <advert-list-component
        lang="{{ app()->getLocale() }}"
        messages-json="{{ json_encode([
            'SelectGender' => t('Choose a sex tutor'),
            'SearchTutor' => t('Find a Teacher'),
            'Search' => t('Search'),
            'PlaceholderFindTeacher' => t('Name, email or phone'),
            'ReadDescription' => t('Read the description'),
            'ReadMore' => t('Read more'),
            'DescriptionOffer' => t('Offer Description'),
            'writeFromPrice' => t('Write from price'),
            'Choose' => t('Choose'),
            'Min' => t('Minutes')
        ]) }}"
    ></advert-list-component>

    <div class="pt-3">
        <p>{!! clean(t($category->content ?? '')) !!}</p>
    </div>
@endsection