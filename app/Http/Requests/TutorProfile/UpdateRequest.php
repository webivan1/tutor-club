<?php

namespace App\Http\Requests\TutorProfile;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;
use App\Entity\TutorProfile;

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
        $rules = [
            'country_code' => [
                'required',
                'string',
                Rule::in(config('country_code'))
            ],
            'phone' => [
                'required',
                'numeric',
                Rule::unique('tutor_profile', 'phone')
                    ->whereNotNull('country_code')
                    ->where('country_code', $this->input('country_code'))
                    ->where('phone_verified', true)
                    ->ignore(TutorProfile::findModel()->phone, 'phone')
            ],
            'gender' => [
                'required',
                'string',
                Rule::in(array_keys((new TutorProfile)->genders()))
            ],
            //'photo' => 'mimes:jpeg,jpg,gif,png|max:10000',
        ];

        foreach (\LaravelLocalization::getSupportedLanguagesKeys() as $lang) {
            if ($lang === app()->getLocale()) {
                $rules['description.' . $lang] = 'string|max:255|required';
                $rules['content.' . $lang] = 'string|required';
            }
        }

        return $rules;
    }
}
