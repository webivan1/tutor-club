@extends('layouts.cabinet')

@section('nav-left')
    {{ Html::link(route('profile.home'), 'Личный профиль', [
        'class' => 'list-group-item'
    ]) }}
    {{ Html::link(route('profile.tutor.home'), 'Стать репетитором', [
        'class' => 'list-group-item text-warning'
    ]) }}
@endsection

@section('content')
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-8">
                <div class="card">
                    <div class="card-header">Спасибо за регистрацию!</div>
                    <div class="card-body">
                        <div>Добро пожаловать в кабинет</div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
