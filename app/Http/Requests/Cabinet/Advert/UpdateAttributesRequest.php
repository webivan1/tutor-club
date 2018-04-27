<?php

namespace App\Http\Requests\Cabinet\Advert;

use App\Entity\Attribute;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdateAttributesRequest extends FormRequest
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
        $attributes = $this->advert->getAllAttributes();

        $rules = [];

        /** @var Attribute $attribute */
        foreach ($attributes ?? [] as $attribute) {
            $key = "attr.{$attribute->id}";
            $rule = [];

            if ($attribute->required) {
                array_push($rule, 'required');
            } else {
                array_push($rule, 'nullable');
            }

            if ($attribute->isNumber()) {
                array_push($rule, 'integer');
            } elseif ($attribute->isFloat()) {
                array_push($rule, 'numeric');
            } elseif ($attribute->isText()) {
                array_push($rule, 'string');
            } elseif ($attribute->isCheckbox()) {
                array_push($rule, 'boolean');
            } elseif ($attribute->isSelect() || $attribute->isRadio()) {
                array_push($rule, Rule::in(array_keys($attribute->variantsToArray())));
            }

            $rules[$key] = $rule;
        }

        return $rules;
    }
}
