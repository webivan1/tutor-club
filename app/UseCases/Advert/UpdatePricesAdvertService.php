<?php
/**
 * Created by PhpStorm.
 * User: Ivan
 * Date: 20.04.2018
 * Time: 0:23
 */

namespace App\UseCases\Advert;

use App\Entity\Advert\Advert;
use App\Entity\Advert\AdvertAttribute;
use App\Entity\Advert\AdvertPrice;
use App\Entity\Attribute;
use App\Events\Advert\ChangeAdvert;
use App\Helpers\ArrayHelper;

class UpdatePricesAdvertService
{
    /**
     * @param Advert $advert
     * @param array $pricesRequest
     */
    public function update(Advert $advert, array $pricesRequest): void
    {
        $prices = $advert->prices()->pluck('id', 'id')->toArray();

        $pricesRequest = array_map(function ($item) use ($advert) {
            return array_merge($item, ['advert_id' => $advert->id]);
        }, ArrayHelper::multipleDataFormToCorrectArray($pricesRequest));

        // Init events
        $this->registerEventChanges($advert);

        foreach ($pricesRequest as $item) {
            extract($item);

            $model = $advert->prices()->updateOrCreate(
                compact('advert_id', 'category_id', 'minutes', 'price_type'),
                compact('price_from')
            );

            $prices = array_diff($prices, [$model->id]);
        }

        if (!empty($prices)) {
            AdvertPrice::destroy($prices);
        }
    }

    /**
     * @param Advert $advert
     */
    private function registerEventChanges(Advert $advert): void
    {
        // Если добавлена новая категория,
        // то сбрасываем статус на черновик
        AdvertPrice::creating(function (AdvertPrice $model) use ($advert) {
            $existCategory = AdvertPrice::where('category_id', $model->category_id)
                ->where('advert_id', $advert->id)
                ->first();

            // Добавлена новая категория, сбрасываем статус объявления
            !empty($existCategory) ?: $advert->toStatusDraft();
        });

        AdvertPrice::saved(function (AdvertPrice $model) use ($advert) {
            $this->triggerUpdatedAdvert($advert);
        });

        // После удаления категории
        // синхронизируем атрибуты объявления
        AdvertPrice::deleted(function (AdvertPrice $model) use ($advert) {
            $this->syncAdvertAttributes($advert);
        });
    }

    /**
     * Запускаем вручную событие, чтобы elasticsearch пересохранил данные
     *
     * @param Advert $advert
     * @return void
     */
    private function triggerUpdatedAdvert(Advert $advert): void
    {
        Advert::updated(function (Advert $advert) {
            event(new ChangeAdvert($advert, ChangeAdvert::EVENT_UPDATE));
        });

        $advert->updateCurrentTimestamp();
    }

    /**
     * @param Advert $advert
     */
    private function syncAdvertAttributes(Advert $advert): void
    {
        $attributes = array_keys($advert->getAllAttributes());
        $advert->attributeValues()
            ->whereNotIn('attribute_id', $attributes)
            ->delete();
    }
}