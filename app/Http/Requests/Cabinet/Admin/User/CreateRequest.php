<?php

namespace App\Http\Requests\Cabinet\Admin\User;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;
use App\Entity\Admin\User;

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
            'name' => 'required|string|max:150',
            'email' => 'required|email|unique:users',
            'role' => 'required|numeric|exists:roles,id',
            'status' => [
                'required',
                Rule::in(array_keys(User::statusesLabels()))
            ]
        ];
    }
}
