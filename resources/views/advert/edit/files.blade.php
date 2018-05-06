@extends('layouts.advert-edit')

@section('content')
    <h1 class="page-header">
        #{{ $advert->id }} {{ t($advert->title) }} - {{ t('home.editAdvertPhotoHeading') }}
    </h1>

    <hr />

    @parent

    @if ($files)
        <div class="table-responsive">
            <table class="table">
                @foreach ($files as $file)
                    <tr>
                        <td width="200">
                            <img src="{{ $file->file_path }}" alt="" class="img-fluid" />
                        </td>
                        <td>
                            {{ Form::open([
                                'method' => 'DELETE',
                                'url' => route('cabinet.advert.delete.file', [$advert, $file]),
                                'onsubmit' => 'return confirm("' . t('home.AreYouSure') . '");'
                            ]) }}
                                {{ Form::submit(t('Delete'), ['class' => 'btn btn-dander']) }}
                            {{ Form::close() }}
                        </td>
                    </tr>
                @endforeach
            </table>
        </div>
    @endif

    <div>
        {{ Form::open([
            'method' => 'PUT',
            'url' => route('cabinet.advert.update.files', $advert),
            'enctype' => 'multipart/form-data'
        ]) }}

            <div class="form-group">
                {{ Form::file('photo[]', [
                    'multiple' => true,
                    'class' => 'form-control'
                ]) }}
            </div>

            {{ Form::submit(t('home.ButtonUpload'), ['class' => 'btn btn-raised btn-success']) }}
        {{ Form::close() }}
    </div>
@endsection