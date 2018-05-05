<?php
/**
 * Created by PhpStorm.
 * User: Ivan
 * Date: 19.04.2018
 * Time: 17:04
 */

namespace App\Http\Controllers\Cabinet\Advert;

use App\Entity\Advert\Advert;
use App\Entity\Advert\AdvertAttribute;
use App\Http\Controllers\Controller;
use App\Http\Requests\Cabinet\Advert\UpdateAttributesRequest;

class AttributeController extends Controller
{
    /**
     * CreateController constructor.
     */
    public function __construct()
    {
        $this->middleware('can:own-update-advert,advert');
    }

    /**
     * @param Advert $advert
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function edit(Advert $advert)
    {
        $attributes = $advert->getAllAttributes();
        return view('advert.edit.attribute', compact('advert', 'attributes'));
    }

    /**
     * @param UpdateAttributesRequest $request
     * @param Advert $advert
     * @return \Illuminate\Http\RedirectResponse
     */
    public function update(UpdateAttributesRequest $request, Advert $advert)
    {
        $this->deleteAttributes($request, $advert);
        $this->updateAttributesFromRequest($request, $advert);

        return back()->with('success', t('home.updateAttributesSuccess'));
    }

    /**
     * @param UpdateAttributesRequest $request
     * @param Advert $advert
     */
    private function deleteAttributes(UpdateAttributesRequest $request, Advert $advert): void
    {
        $existAttributes = $advert->attributeValues()->pluck('attribute_id')->toArray();
        $requestAttr = $request->input('attr') ?? [];
        $deletes = array_diff($existAttributes, array_keys($requestAttr));

        if (!empty($deletes)) {
            $advert->attributeValues()
                ->whereIn('attribute_id', $deletes)
                ->delete();

            $this->triggerUpdatedAdvert($advert);
        }
    }

    /**
     * @param UpdateAttributesRequest $request
     * @param Advert $advert
     */
    private function updateAttributesFromRequest(UpdateAttributesRequest $request, Advert $advert): void
    {
        AdvertAttribute::saved(function (AdvertAttribute $model) use ($advert) {
            $this->triggerUpdatedAdvert($advert);
        });

        foreach ($request->input('attr') ?? [] as $attr_id => $value) {
            AdvertAttribute::updateOrCreate([
                'advert_id' => $request->advert->id,
                'attribute_id' => $attr_id
            ], ['value' => $value]);
        }
    }

    /**
     * Запускаем вручную событие, чтобы elasticsearch пересохранил данные
     *
     * @param Advert $advert
     * @return void
     */
    private function triggerUpdatedAdvert(Advert $advert): void
    {
        $advert->updateCurrentTimestamp();
    }
}