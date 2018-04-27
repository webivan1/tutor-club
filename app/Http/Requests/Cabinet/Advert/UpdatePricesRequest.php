<?php

namespace App\Http\Requests\Cabinet\Advert;

use Illuminate\Foundation\Http\FormRequest;

class UpdatePricesRequest extends FormRequest
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
            'prices.category_id.*' => 'required|integer',
            'prices.price_from.*' => 'required|numeric',
            'prices.price_type.*' => 'required|string',
            'prices.minutes.*' => 'nullable|integer'
        ];
    }
}
