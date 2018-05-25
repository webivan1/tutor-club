<?php

use App\Entity\TutorProfile;

/**
 * @var TutorProfile $model
 */

?>

@extends('layouts.profile')

@section('script.head')
    <script src="//cdn.ckeditor.com/4.9.1/basic/ckeditor.js"></script>
@endsection

@section('content')
    @parent

    <div class="row">
        {{ Form::open([
            'method' => !$model->id ? 'POST' : 'PUT',
            'url' => route('profile.tutor.form'),
            'class' => 'col-md-7',
            'enctype' => 'multipart/form-data'
        ]) }}

            <div class="card">
                <div class="card-body">
                    <div class="form-group">
                        {{ Form::label('phone', t('Phone'), ['class' => 'bmd-label-floating']) }}

                        <div class="row">
                            <div class="col-sm-4">
                                {{ Form::input('text', 'country_code', old('country_code', $model->country_code), [
                                    'class' => 'form-control ' . (!$errors->has('country_code') ?: ' is-invalid'),
                                    'placeholder' => ''
                                ]) }}
                                <small class="text-muted">{{ t('Country Code (Example: +382)') }}</small>
                            </div>
                            <div class="col-sm-8">
                                {{ Form::input('number', 'phone', old('phone', $model->phone), [
                                    'class' => 'form-control ' . (!$errors->has('phone') ?: ' is-invalid'),
                                    'placeholder' => ''
                                ]) }}
                                <small class="text-muted">{{ t('Number phone (Example: 91199988)') }}</small>
                            </div>
                        </div>

                        @if ($errors->has('country_code') || $errors->has('phone'))
                            <span class="text-danger">
                                {{ !$errors->has('country_code') ? '' : $errors->first('country_code') }}
                                {{ !$errors->has('phone') ? '' : $errors->first('phone') }}
                            </span>
                        @endif
                    </div>

                    <div class="form-group">
                        {{ Form::label('gender', t('Select a gender'), ['class' => 'bmd-label-floating']) }}
                        {{ Form::select('gender', ['' => 'Выбрать'] + $model->genders(), old('gender', $model->gender), [
                            'class' => 'form-control ' . (!$errors->has('gender') ?: ' is-invalid')
                        ]) }}
                        @if ($errors->has('gender'))
                            <span class="invalid-feedback">
                                <strong>{{ $errors->first('gender') }}</strong>
                            </span>
                        @endif
                    </div>

                    <div class="form-group">
                        @if ($model->id && $model->image)
                            <div class="mb-2">
                                {{ Html::image(Storage::url($model->image->file_path) . '?t=' . time(), 'Аватар', [
                                    'class' => 'img-thumbnail'
                                ]) }}
                            </div>
                        @endif

                        {{ Form::label('photo', t('Avatar'), ['class' => 'bmd-label-floating']) }}
                        {{ Form::file('photo', [
                            'class' => 'form-control ' . (!$errors->has('photo') ?: ' is-invalid'),
                            'value' => !$model->image ?: $model->image->file_path
                        ]) }}
                        @if ($errors->has('photo'))
                            <span class="invalid-feedback">
                                <strong>{{ $errors->first('photo') }}</strong>
                            </span>
                        @endif
                    </div>

                </div>
            </div>

            <hr />

            <h3>{{ t('home.formTutorProfileHeading') }}</h3>
            <div class="help-block mb-4">
                {{ t('home.formTutorDescription') }}
            </div>

            @foreach(LaravelLocalization::getSupportedLocales() as $lang => $locale)
                <div class="card mb-3">
                    <div class="card-header">
                        {{ $locale['native'] }}
                    </div>
                    <div class="card-body">
                        <div class="form-group">
                            {{ Form::label("description[{$lang}]", t('home.smallDescription')) }}
                            {{ Form::textarea("description[{$lang}]", old("description[{$lang}]", $model->getText('description', $lang)), [
                                'class' => 'cke-editor form-control ' . (!$errors->has('description.' . $lang) ?: ' is-invalid'),
                            ]) }}
                            @if ($errors->has('description.' . $lang))
                                <span class="invalid-feedback">
                                    <strong>{{ $errors->first('description.' . $lang) }}</strong>
                                </span>
                            @endif
                        </div>
                        <div class="form-group">
                            {{ Form::label('content-' . $lang, t('home.largeDescription')) }}
                            {{ Form::textarea("content[{$lang}]", old("content[{$lang}]", $model->getText('content', $lang)), [
                                'class' => 'form-control ' . (!$errors->has('content.' . $lang) ?: ' is-invalid'),
                                'id' => 'content-' . $lang
                            ]) }}
                            <script type="text/javascript">
                                CKEDITOR.replace('{{ 'content-' . $lang }}');
                            </script>
                            @if ($errors->has('content.' . $lang))
                                <span class="invalid-feedback">
                                    <strong>{{ $errors->first('content.' . $lang) }}</strong>
                                </span>
                            @endif
                        </div>
                    </div>
                </div>
            @endforeach

            <button type="submit" class="btn btn-raised btn-primary">
                {{ t('Save') }}
            </button>

        {{ Form::close() }}
    </div>
@endsection