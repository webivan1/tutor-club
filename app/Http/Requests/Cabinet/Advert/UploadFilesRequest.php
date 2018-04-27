<?php

namespace App\Http\Requests\Cabinet\Advert;

use Illuminate\Foundation\Http\FormRequest;

class UploadFilesRequest extends FormRequest
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
            'photo' => 'required|array',
            'photo.*' => 'required|mimes:jpeg,jpg,gif,png|max:6000'
        ];
    }
}
