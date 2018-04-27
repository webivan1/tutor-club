<?php

namespace App\Http\Requests\Cabinet\Admin\Lang;

use Illuminate\Foundation\Http\FormRequest;

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
        return [
            'value' => 'required|string|max:10|unique:languages,id,' . $this->lang->id,
            'name' => 'required|string|max:20',
            'native' => 'required|string|max:20',
            'regional' => 'required|string|max:10'
        ];
    }
}
