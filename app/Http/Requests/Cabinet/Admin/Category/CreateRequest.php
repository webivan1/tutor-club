<?php

namespace App\Http\Requests\Cabinet\Admin\Category;

use Illuminate\Foundation\Http\FormRequest;

class CreateRequest extends FormRequest
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
            'title' => 'required|string|max:120',
            'description' => 'required|string|max:200',
            'name' => 'required|string|max:200',
            'slug' => 'required|string|max:200|unique:category',
            'parent_id' => 'nullable|numeric|exists:category,id',
        ];
    }
}
