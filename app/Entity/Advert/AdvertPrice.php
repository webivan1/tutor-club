<?php

namespace App\Entity\Advert;

use App\Entity\Category;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Query\Expression;

/**
 * @property integer $id
 * @property integer $advert_id
 * @property integer $category_id
 * @property string $hint
 * @property float $price_from
 * @property float $price_to
 * @property integer $minutes
 * @property string $price_type
 */
class AdvertPrice extends Model
{
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'advert_id', 'category_id', 'price_from',
        'price_to', 'minutes', 'price_type', 'hint'
    ];

    /**
     * @var string
     */
    public $table = 'advert_prices';

    /**
     * @var boolean
     */
    public $timestamps = false;

    public const TYPE_RUB = 'rub';
    public const TYPE_USD = 'usd';
    public const TYPE_EUR = 'eur';

    /**
     * @return array
     */
    public static function types(): array
    {
        return [
            self::TYPE_RUB => '₽',
            self::TYPE_USD => '$',
            self::TYPE_EUR => '€'
        ];
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasOne
     */
    public function category()
    {
        return $this->hasOne(Category::class, 'id', 'category_id');
    }

    /**
     * @param int $categoryId
     * @return array
     */
    public static function getMinMaxPriceByCategory(int $categoryId): array
    {
        return \Cache::tags([
            (new self)->getTable(),
            (new Advert)->getTable()
        ])->remember('prices-' . $categoryId, 360, function () use ($categoryId) {
            return self::select([
                new Expression('MIN(price_from) AS min'),
                new Expression('MAX(price_from) AS max'),
                'price_type'
            ])
                ->where('category_id', $categoryId)
                ->groupBy('price_type')
                ->get()
                ->toArray();
        });
    }
}
