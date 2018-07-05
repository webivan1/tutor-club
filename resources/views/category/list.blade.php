<?php

$themes = [
    //['bg-dark', 'text-white'],
    //['bg-danger', 'text-white'],
    //['bg-primary', 'text-white'],
    //['bg-info', 'text-white'],
    //['bg-warning', 'text-grey-900'],
    //['bg-secondary', 'text-white'],
    //['bg-success', 'text-white'],
    //['bg-lime', 'text-grey-900'],
    //['bg-light-green', 'text-grey-900'],
    //['bg-light-blue', 'text-white']
    ['', '']
];

?>

@extends('layouts.app')

@section('title', t('TitleCategoryList'))
@section('description', t('DescriptionCategoryList'))
{{--@section('width-content', 'col-md-10')--}}
@section('h1', t('What will learn?'))

@section('content')
    <div class="mb-3">
        <p>Выберите услугу и найдите своего специалиста на TutorClub</p>
    </div>

    @foreach (array_chunk($categories, 3) as $categoryGroup)
        <div class="row">
            @foreach ($categoryGroup as $category)
                <?php
                    $style = array_random($themes);
                ?>

                <div class="col-md-4 mb-lg-5 mb-4">
                    <div class="card {{ $style[0] }} {{ $style[1] }}">
                        <div class="card-header">
                            <h5 class="card-title my-0">
                                <a class="{{ $style[1] }}" href="{{ route('category.show', $category['slug']) }}">
                                    <span>
                                        {{ t($category['name']) }}
                                    </span>
                                    <small>
                                        {{ $category['total_adverts'] > 0 ? $category['total_adverts'] : '' }}
                                    </small>
                                </a>
                            </h5>
                        </div>
                        <div class="card-body">
                            @if (!empty($category['children']))
                                <?php
                                    $categoriesChild = array_slice($category['children'], 0, 3);
                                ?>

                                @foreach ($categoriesChild as $child)
                                    <div class="mb-2">
                                        <a class="{{ $style[1] }}" href="{{ route('category.show', $child['slug']) }}">
                                            <span>
                                                {{ t($child['name']) }}
                                            </span>
                                            <small>
                                                {{ $child['total_adverts'] > 0 ? $child['total_adverts'] : '' }}
                                            </small>
                                        </a>
                                    </div>
                                @endforeach
                            @endif
                        </div>
                        @if (!empty($category['children']) && count($category['children']) > 3)
                            <div class="card-footer">
                                <a class="{{ $style[1] }}" href="{{ route('category.show', $category['slug']) }}">
                                    {{ t('Show all') }}
                                    <small>
                                        {{ $category['total_categories'] }}
                                    </small>
                                </a>
                            </div>
                        @endif
                    </div>
                </div>
            @endforeach
        </div>
    @endforeach
@endsection