<?php

namespace App\Http\Requests\Cabinet\Admin\Attribute;

use App\Entity\Attribute;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

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
            'label' => 'required|string|max:150',
            'type' => [
                'required',
                Rule::in(array_keys(Attribute::types()))
            ],
            'variants' => 'nullable|string',
            'required' => 'boolean',
            'sort' => 'nullable|integer'
        ];
    }
}
