<?php

namespace App\Http\Requests\Cabinet\Admin\Media\Category;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;
use App\Entity\Admin\Media\Category;

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
            'title' => 'required|string|max:50',
            'description' => 'nullable|string|max:50',
            'content' => 'nullable|string|max:50',
            'heading' => 'nullable|string|max:50',
            'slug' => 'required|string|max:200|unique:news_category,id,' . $this->category->id,
            'status' => [
                'required',
                Rule::in(array_keys((new Category())->statusesLabels()))
            ],
        ];
    }
}
