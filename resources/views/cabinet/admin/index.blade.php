@extends('layouts.admin')

@section('title', '| Admin page')

@section('content')
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-body">
                    Hello {{ Auth::user()->name }}
                </div>
            </div>
        </div>
    </div>
@endsection