@extends('layouts.app')

@section('title', t($category->title))
@section('description', t($category->description))
@section('width-content', 'col-md-12')

@section('content')
    <div class="card mb-5">
        <div class="card-body">
            <h1>{{ t($category->name) }}</h1>
        </div>
    </div>

    @if(!empty($childCategories))
        <div class="row mb-3">
            @foreach (chunk_column($childCategories, 3) as $categoryGroup)
                <div class="col-md-4">
                    @foreach ($categoryGroup as $item)
                        <div class="mb-2">
                            <a href="{{ route('category.show', $item['slug']) }}">
                            <span class="text-muted">
                                {{ t($item['name']) }}
                            </span>
                                <small class="text-muted">
                                    {{ $item['total_adverts'] > 0 ? $item['total_adverts'] : '' }}
                                </small>
                            </a>
                        </div>
                    @endforeach
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
            'PlaceholderFindTeacher' => t('Name, email or phone')
        ]) }}"
    ></advert-list-component>
@endsection