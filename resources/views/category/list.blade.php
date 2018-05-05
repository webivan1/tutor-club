@extends('layouts.app')

@section('title', t('TitleCategoryList'))
@section('description', t('DescriptionCategoryList'))
@section('width-content', 'col-md-10')

@section('content')
    <div class="card mb-5">
        <div class="card-body">
            <h1>{{ t('What will learn?') }}</h1>

            <p>Выберите услугу и найдите своего специалиста на TutorClub</p>
        </div>
    </div>

    @foreach (array_chunk($categories, 3) as $categoryGroup)
        <div class="row">
            @foreach ($categoryGroup as $category)
                <div class="col-md-4 mb-lg-5 mb-4">
                    <h5>
                        <a href="{{ route('category.show', $category['slug']) }}">
                            <span class="text-dark">
                                {{ t($category['name']) }}
                            </span>
                            <small class="text-muted">
                                {{ $category['total_adverts'] > 0 ? $category['total_adverts'] : '' }}
                            </small>
                        </a>
                    </h5>

                    @if (!empty($category['children']))
                        <?php
                            $categoriesChild = array_slice($category['children'], 0, 3);
                        ?>

                        @foreach ($categoriesChild as $child)
                            <div class="mb-2">
                                <a href="{{ route('category.show', $child['slug']) }}">
                                    <span class="text-muted">
                                        {{ t($child['name']) }}
                                    </span>
                                    <small class="text-muted">
                                        {{ $child['total_adverts'] > 0 ? $child['total_adverts'] : '' }}
                                    </small>
                                </a>
                            </div>
                        @endforeach

                        @if (count($category['children']) > 3)
                            <div class="mb-2">
                                <a href="{{ route('category.show', $category['slug']) }}">
                                    {{ t('Show all') }}
                                    <small class="text-muted">
                                        {{ $category['total_categories'] }}
                                    </small>
                                </a>
                            </div>
                        @endif
                    @endif
                </div>
            @endforeach
        </div>
    @endforeach
@endsection