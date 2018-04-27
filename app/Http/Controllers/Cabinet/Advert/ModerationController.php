<?php
/**
 * Created by PhpStorm.
 * User: Ivan
 * Date: 23.04.2018
 * Time: 10:50
 */

namespace App\Http\Controllers\Cabinet\Advert;

use App\Entity\Advert\Advert;
use App\Entity\Advert\AdvertPrice;
use App\Entity\Attribute;
use App\Http\Controllers\Controller;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\Validator;

class ModerationController extends Controller
{
    /**
     * CreateController constructor.
     */
    public function __construct()
    {
        $this->middleware([
            'can:own-update-advert,advert',
            'can:moderation-advert,advert'
        ]);
    }

    /**
     * @param Advert $advert
     * @return $this|\Illuminate\Http\RedirectResponse
     */
    public function index(Advert $advert)
    {
        $validator = Validator::make(
            $this->getModels($advert),
            $this->rules($advert),
            $this->messages()
        );

        if (!$validator->fails()) {
            $advert->update(['status' => Advert::STATUS_MODERATION]);
            return redirect()->route('cabinet.advert.index')
                ->with('success', t('home.toModerationSuccess'));
        }

        return view('advert.edit.moderation', compact('advert'))
            ->withErrors($validator);
    }

    /**
     * @param Advert $advert
     * @return array
     */
    private function getModels(Advert $advert): array
    {
        $advert->prices;
        $model = $advert->toArray();
        $model['attributes'] = array_map(function (Attribute $item) {
            return $item->toArray();
        }, $advert->getAllAttributes());

        return $model;
    }

    /**
     * @param Advert $advert
     * @return array
     */
    private function rules(Advert $advert): array
    {
        $rules = [
            'description' => 'required|string',
            'lang' => ['required', Rule::in(\LaravelLocalization::getSupportedLanguagesKeys())],
            // Prices & category
            'prices' => 'required|array',
            'prices.*.category_id' => 'required|integer|exists:category,id',
            'prices.*.price_from' => 'required|integer',
            'prices.*.price_type' => ['required', Rule::in(array_keys(AdvertPrice::types()))],
            'prices.*.minutes' => 'nullable|integer',
        ];

        /** @var Attribute[] $attributes */
        $attributes = $advert->getAllAttributes();

        foreach ($attributes as $attribute) {
            $key = "attributes.{$attribute->id}.value";

            if ($attribute->required) {
                $rules[$key] = ['required'];

                if ($attribute->isSelect() || $attribute->isRadio()) {
                    $rules[$key][] = Rule::in(array_keys($attribute->variantsToArray()));
                }
            }
        }

        return $rules;
    }

    /**
     * @return array
     */
    private function messages(): array
    {
        return [
            'description' => 'Заполните описание в разделе общая информация',
            'lang' => 'Укажите языковую пренадлежность в разделе общая информация',
            'prices' => 'Выберите подкатегории и цены в разделе Цены',
            'prices.*' => 'Выберите подкатегории и цены в разделе Цены',
            'attributes.*' => 'Заполните обязательные поля в разделе Дополнительные атрибуты'
        ];
    }
}