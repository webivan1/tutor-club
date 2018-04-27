@extends('layouts.advert-edit')

@section('content')
    <h1 class="page-header">
        #{{ $advert->id }} {{ $advert->title }} - {{ t('home.editAdvertAttributesHeading') }}
    </h1>

    <hr />

    @parent

    @if (!empty($attributes))
        <div class="row">
            <div class="col-md-7">
                {{ Form::open(['method' => 'PUT', 'url' => route('cabinet.advert.update.attribute', $advert)]) }}

                    <div class="card">
                        <div class="card-body">
                            @foreach ($attributes as $attribute)
                                <div class="form-group">
                                    @switch ($attribute->type)
                                        @case ($attribute->isStyleInlineField())
                                        {{ Form::label("attr-" . $attribute->id, t($attribute->label)) }}
                                        @if ($attribute->isNumber() || $attribute->isFloat())
                                            {{ Form::input('number', "attr[{$attribute->id}]", old("attr[{$attribute->id}]", $attribute->value), [
                                                'class' => 'form-control',
                                                'required' => $attribute->required
                                            ]) }}
                                        @elseif ($attribute->isText())
                                            {{ Form::text("attr[{$attribute->id}]", old("attr[{$attribute->id}]", $attribute->value), [
                                                'class' => 'form-control',
                                                'required' => $attribute->required
                                            ]) }}
                                        @elseif ($attribute->isSelect())
                                            {{ Form::select("attr[{$attribute->id}]", $attribute->variantsToArray(), old("attr[{$attribute->id}]", $attribute->value), [
                                                'class' => 'form-control',
                                                'required' => $attribute->required
                                            ]) }}
                                        @endif
                                        @break
                                        @case ($attribute->isStyleCheckField())
                                        @if ($attribute->isCheckbox())
                                            <div class="checkbox">
                                                <label>
                                                    {{ Form::checkbox("attr[{$attribute->id}]", 1, old("attr[{$attribute->id}]", !empty($attribute->value)) ? true : false) }}
                                                    {{ t($attribute->label) }}
                                                </label>
                                            </div>
                                        @elseif ($attribute->isRadio())
                                            @foreach ($attribute->variantsToArray() as $key => $value)
                                                <div class="radio">
                                                    <label>
                                                        {{ Form::radio("attr[{$attribute->id}]", $key, old("attr[{$attribute->id}]", $attribute->value) === $key ? true : false) }}
                                                        {{ t($value) }}
                                                    </label>
                                                </div>
                                            @endforeach
                                        @endif
                                        @break
                                    @endswitch
                                </div>
                            @endforeach
                        </div>
                    </div>

                    <hr />

                    {{ Form::submit(t('home.Save'), ['class' => 'btn btn-raised btn-success']) }}
                {{ Form::close() }}
            </div>
        </div>
    @endif
@endsection