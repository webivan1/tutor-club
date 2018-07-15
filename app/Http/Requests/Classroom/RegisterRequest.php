<?php

namespace App\Http\Requests\Classroom;

use Illuminate\Foundation\Http\FormRequest;

class RegisterRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return \Auth::check();
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'timezone' => 'required|timezone',
            'published_at' => 'required|date|date_format:Y-m-d H:i',
            'video' => 'nullable|boolean',
            'from' => 'required|integer|exists:users,id',
            'to.*' => 'required|integer|exists:users,id',
            'tutor' => 'required|integer|exists:tutor_profile,id',
            'theme.id' => 'required|integer|exists:advert_prices,id',
            'theme.name' => 'required|string|max:200'
        ];
    }
}
