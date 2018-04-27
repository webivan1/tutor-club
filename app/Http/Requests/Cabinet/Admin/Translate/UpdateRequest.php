<?php

namespace App\Http\Requests\Cabinet\Admin\Translate;

use Illuminate\Foundation\Http\FormRequest;
use LaravelLocalization;

class UpdateRequest extends FormRequest
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
        $languages = array_keys(LaravelLocalization::getSupportedLocales());

        $rules = [
            'name' => 'required|string|max:150|unique:word_key,id,' . $this->id,
            'translate' => 'required|array'
        ];

        foreach ($languages as $language) {
            $rules['translate.' . $language] = 'required|string';
        }

        return $rules;
    }
}
