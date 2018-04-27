<?php
/**
 * Created by PhpStorm.
 * User: Ivan
 * Date: 19.04.2018
 * Time: 16:36
 */

namespace App\UseCases\Advert;

use App\Entity\Advert\Advert;
use App\Entity\Advert\AdvertCategory;
use App\Entity\Advert\AdvertPrice;
use App\Entity\TutorProfile;
use App\Helpers\ArrayHelper;
use Illuminate\Support\Facades\DB;

class CreateAdvertService
{
    /**
     * @param string $title
     * @param string $lang
     * @param string $description
     * @param null|string $presentation
     * @param int $experience
     * @return Advert
     */
    public function create(string $title, string $lang, string $description, ?string $presentation, int $experience): Advert
    {
        /** @var Advert $advert */
        $advert = Advert::make(compact('title', 'lang', 'description', 'presentation', 'experience'));

        return DB::transaction(function () use ($advert) {
            $advert->user()->associate(\Auth::user());
            $advert->profile()->associate(TutorProfile::findModel());

            $advert->status = Advert::STATUS_DRAFT;

            $advert->saveOrFail();

            return $advert;
        });
    }

    /**
     * @param Advert $advert
     * @param array $pricesRequest
     */
    public function createPricesWithCategory(Advert $advert, array $pricesRequest): void
    {
        $prices = ArrayHelper::multipleDataFormToCorrectArray($pricesRequest);

        DB::transaction(function () use ($prices, $advert) {
            foreach ($prices as $price) {
                $model = new AdvertPrice();
                $model->setRawAttributes(array_merge($price, [
                    'advert_id' => $advert->id
                ]));

                $model->saveOrFail();
            }
        });
    }
}