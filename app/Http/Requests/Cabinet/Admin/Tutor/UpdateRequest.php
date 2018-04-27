<?php

namespace App\Http\Requests\Cabinet\Admin\Tutor;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;
use App\Entity\Admin\TutorProfile;

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
            'status' => [
                'required',
                Rule::in(array_keys((new TutorProfile)->statuses()))
            ],
            'comment' => 'string|max:255'
        ];
    }
}
