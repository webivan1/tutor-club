<div class="alert alert-secondary">
    {{ t('Select categories and fill prices') }}
</div>

<div class="clone-prices">
    @foreach($advertPrice as $key => $value)
        <div class="card mb-3 js-item">
            <div class="card-body">
                <div class="row">
                    <div class="col-md-3">
                        <div class="form-group">
                            <label>{{ t('Select a subcategory') }}</label>
                            <select name="prices[category_id][]" class="form-control {{ !$errors->has('prices.category_id.' . $key) ? '' : 'is-invalid' }}">
                                <option value="">{{ t('Choose') }}</option>
                                @foreach ($listCategory as $item)
                                    <option value="{{ $item->id }}" {{ isset($value['category_id']) && $value['category_id'] == $item->id ? 'selected' : '' }}>
                                        {!! $item->depth > 1 ? str_repeat('&nbsp;&nbsp;', $item->depth) : '' !!}
                                        {{ t($item->name) }}
                                    </option>
                                @endforeach
                            </select>
                            @if ($errors->has('prices.category_id.' . $key))
                                <div class="invalid-feedback">
                                    <b>{{ $errors->first('prices.category_id.' . $key) }}</b>
                                </div>
                            @endif
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="form-group">
                            {{ Form::label('price_type', t('Enter currency')) }}
                            {{ Form::select('prices[price_type][]', $types, $value['price_type'] ?? null, [
                                'class' => 'form-control ' . (!$errors->has('prices.price_type.' . $key) ? '' : 'is-invalid')
                            ]) }}
                            @if ($errors->has('prices.price_type.' . $key))
                                <div class="invalid-feedback">
                                    <b>{{ $errors->first('prices.price_type.' . $key) }}</b>
                                </div>
                            @endif
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="form-group">
                            {{ Form::label('price_from', t('Price')) }}
                            {{ Form::input('float', 'prices[price_from][]', $value['price_from'] ?? null, [
                                'class' => 'form-control ' . (!$errors->has('prices.price_from.' . $key) ? '' : 'is-invalid')
                            ]) }}
                            @if ($errors->has('prices.price_from.' . $key))
                                <div class="invalid-feedback">
                                    <b>{{ $errors->first('prices.price_from.' . $key) }}</b>
                                </div>
                            @endif
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="form-group">
                            {{ Form::label('price_from', t('Duration of 1 lesson (minutes)')) }}
                            {{ Form::input('float', 'prices[minutes][]', $value['minutes'] ?? null, [
                                'class' => 'form-control ' . (!$errors->has('prices.minutes.' . $key) ? '' : 'is-invalid')
                            ]) }}
                            @if ($errors->has('prices.minutes.' . $key))
                                <div class="invalid-feedback">
                                    <b>{{ $errors->first('prices.minutes.' . $key) }}</b>
                                </div>
                            @endif
                        </div>
                    </div>
                </div>
                <div class="text-right">
                    <button
                        type="button"
                        onclick="deleteItem(this, '.clone-prices')"
                        class="btn btn-danger bmd-btn-fab bmd-btn-fab-sm"
                    >
                        <i class="material-icons">close</i>
                    </button>
                </div>
            </div>
        </div>
    @endforeach
</div>

<div class="text-right">
    <button type="button" class="btn btn-raised btn-info" data-clone-container=".clone-prices">
        {{ t('Add another subcategory') }} +
    </button>
</div>