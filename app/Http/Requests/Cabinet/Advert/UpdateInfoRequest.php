<?php

namespace App\Http\Requests\Cabinet\Advert;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdateInfoRequest extends FormRequest
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
            'lang' => [
                'required',
                Rule::in(\LaravelLocalization::getSupportedLanguagesKeys())
            ],
            'experience' => 'required|integer',
            'presentation' => 'nullable|url',
            'description' => 'required|string',
        ];
    }
}
