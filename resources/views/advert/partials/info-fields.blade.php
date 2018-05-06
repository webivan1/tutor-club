<div class="row mb-4">
    <div class="col-md-7">
        <div class="card">
            <div class="card-body">
                <div class="form-group">
                    {{ Form::label('lang', t('Specify the language of your property')) }}
                    {{ Form::select('lang', \App\Helpers\LangHelper::langList(), old('lang', $advert->lang ?? app()->getLocale()), [
                        'class' => 'form-control ' . (!$errors->has('lang') ? '' : 'is-invalid')
                    ]) }}
                    @if ($errors->has('lang'))
                        <div class="invalid-feedback">
                            <b>{{ $errors->first('lang') }}</b>
                        </div>
                    @endif
                </div>
                <div class="form-group">
                    {{ Form::label('experience', t('The experience of teaching in this field (years)')) }}
                    {{ Form::input('number', 'experience', old('experience', $advert->experience ?? 0), [
                        'class' => 'form-control ' . (!$errors->has('experience') ? '' : 'is-invalid')
                    ]) }}
                    @if ($errors->has('experience'))
                        <div class="invalid-feedback">
                            <b>{{ $errors->first('experience') }}</b>
                        </div>
                    @endif
                </div>
                <div class="form-group">
                    {{ Form::label('presentation', t('Video presentation (youtube url)')) }}
                    {{ Form::text('presentation', old('presentation', $advert->presentation ?? ''), [
                        'class' => 'form-control ' . (!$errors->has('presentation') ? '' : 'is-invalid')
                    ]) }}
                    @if ($errors->has('presentation'))
                        <div class="invalid-feedback">
                            <b>{{ $errors->first('presentation') }}</b>
                        </div>
                    @endif
                </div>
                <div class="form-group">
                    {{ Form::label('description-cke', t('Write briefly about your experience in this field')) }}
                    {{ Form::textarea('description', old('description', $advert->description ?? ''), [
                        'class' => 'form-control',
                        'id' => 'description-cke'
                    ]) }}
                    @if ($errors->has('description'))
                        <small class="text-danger">
                            <b>{{ $errors->first('description') }}</b>
                        </small>
                    @endif
                    <script type="text/javascript">
                      //CKEDITOR.config.allowedContent = true;
                      CKEDITOR.replace('description-cke');
                    </script>
                </div>
            </div>
        </div>
    </div>
</div>