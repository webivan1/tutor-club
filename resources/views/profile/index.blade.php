@extends('layouts.profile')

@section('content')
    @parent

    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-8">
                <div class="card">
                    <div class="card-header">
                        {{ Html::link(route('profile.edit.form'), t('home.EditProfile'), [
                            'class' => 'btn btn-primary mr-2'
                        ]) }}
                        {{ Html::link(route('profile.password.form'), t('home.ChangePassword'), [
                            'class' => 'btn btn-primary mr-2'
                        ]) }}
                        {{ Html::link(route('profile.email.form'), t('home.ChangeEmail'), [
                            'class' => 'btn btn-primary'
                        ]) }}
                    </div>

                    <table class="table table-striped mb-0">
                        <tr>
                            <td>Ваш номер в системе</td>
                            <td>{{ $user->id }}</td>
                        </tr>
                        <tr>
                            <td>Имя</td>
                            <td>{{ $user->name }}</td>
                        </tr>
                        <tr>
                            <td>Email</td>
                            <td>{{ $user->email }}</td>
                        </tr>
                        <tr>
                            <td>Дата регистрации</td>
                            <td>{{ date('d.m.Y H:i', strtotime($user->created_at)) }}</td>
                        </tr>
                    </table>
                </div>
            </div>
        </div>
    </div>
@endsection