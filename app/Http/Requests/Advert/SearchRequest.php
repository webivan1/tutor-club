<?php

namespace App\Http\Requests\Advert;

use App\Entity\Advert\AdvertPrice;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class SearchRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'priceType' => [
                Rule::in(array_keys(AdvertPrice::types()))
            ],
            'priceFrom' => 'numeric',
            ''
        ];
    }
}
