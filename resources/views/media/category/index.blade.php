@extends('layouts.app')

@section('h1', 'media')


@section('content')
    <div class="card">
        <div class="card-body">
            <h1>
                {{ $category->slug }}
            </h1>
        </div>
    </div>
@endsection

