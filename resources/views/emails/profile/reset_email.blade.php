@extends('layouts.email')

@section('content')
    @parent

    <p>Чтобы активировать данную почту введите это ключ: <b>{{ $model->token }}</b></p>
    <p>Если вы ничего не запрашивали, то просто проигнорируйте это письмо!</p>
@endsection