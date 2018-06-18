<?php

namespace App\Http\Requests\Cabinet\Admin\Media\News;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;
use App\Entity\Admin\Media\News;

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
            'heading' => 'nullable|string|max:200',
            'slug' => 'required|string|max:200|unique:news,id,' . $this->news->id,
            'category_id' => 'required|integer',
            'content' => 'nullable|string',
            'photo' => 'sometimes|required|mimes:jpeg,jpg,gif,png|max:10000',
            'title' => 'nullable|string|max:200',
            'description' => 'nullable|string',
            'lang' => [
                'required',
                Rule::in(\LaravelLocalization::getSupportedLanguagesKeys())
            ],
            'status' => [
                'required',
                Rule::in(array_keys((new News())->statusesLabels()))
            ],
            'published_at' => 'nullable|date'
        ];

    }
}
