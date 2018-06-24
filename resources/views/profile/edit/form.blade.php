@extends('layouts.profile')

@section('content')
    @parent

    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-body">
                    {{ Form::open([
                        'method' => 'POST',
                        'url' => route('profile.edit.form'),
                        'enctype' => 'multipart/form-data'
                    ]) }}
                        <div class="form-group">
                            {{ Form::label('name', t('home.Username')) }}
                            {{ Form::input('name', 'name', old('name', $user->name), [
                                'class' => 'form-control ' . (!$errors->has('name') ?: 'is-invalid')
                            ]) }}
                            @if ($errors->has('name'))
                                <span class="invalid-feedback">
                                    <strong>{{ $errors->first('name') }}</strong>
                                </span>
                            @endif
                        </div>

                        @if ($user->image)
                            <div class="row mb-1">
                                <div class="col-3">
                                    {{ Html::image(Storage::url($user->image->getPreset('350x350')), '', [
                                        'class' => 'img-thumbnail w-100'
                                    ]) }}
                                </div>
                                <div class="col">
                                    {{ Html::link(route('profile.edit.delete.image'), t('Delete image'), [
                                        'class' => 'btn btn-danger',
                                        'onclick' => 'return confirm("Are you sure?")'
                                    ]) }}
                                </div>
                            </div>
                        @endif

                        <div class="form-group">
                            {{ Form::label('photo', t('Avatar upload')) }}
                            {{ Form::file('photo', ['class' => 'form-control']) }}
                            @if ($errors->has('photo'))
                                <span class="invalid-feedback">
                                    <strong>{{ $errors->first('photo') }}</strong>
                                </span>
                            @endif
                        </div>

                        {{ Form::submit(t('home.Save'), ['class' => 'btn btn-raised btn-success']) }}
                    {{ Form::close() }}
                </div>
            </div>
        </div>
    </div>
@endsection