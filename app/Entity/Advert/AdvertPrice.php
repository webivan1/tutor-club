<?php

namespace App\Entity\Advert;

use App\Entity\Category;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Query\Expression;
use Illuminate\Database\Query\JoinClause;
use Illuminate\Support\Collection;

/**
 * @property integer $id
 * @property integer $advert_id
 * @property integer $category_id
 * @property string $hint
 * @property float $price_from
 * @property float $price_to
 * @property integer $minutes
 * @property string $price_type
 *
 * @property Category $category
 * @property Advert $advert
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
     * Get currency by lang
     *
     * @param string|null $lang
     * @return string
     */
    public static function getCurrencyByLang(?string $lang = null): string
    {
        switch ($lang ?? app()->getLocale()) {
            case 'ru' : return self::TYPE_RUB; break;
            case 'en' : return self::TYPE_USD; break;
            default : return self::TYPE_EUR; break;
        }
    }

    /**
     * Get lang by currency
     *
     * @param string $currency
     * @return string
     */
    public static function getLangByCurrency(string $currency): string
    {
        $data = [];

        foreach (\LaravelLocalization::getSupportedLanguagesKeys() as $lang) {
            $data[self::getCurrencyByLang($lang)] = $lang;
        }

        return $data[$currency] ?? app()->getLocale();
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasOne
     */
    public function category()
    {
        return $this->hasOne(Category::class, 'id', 'category_id');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function advert()
    {
        return $this->belongsTo(Advert::class, 'advert_id', 'id');
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

    /**
     * All prices by tutor and advert
     *
     * @param int $tutor
     * @param int|null $advert
     * @return Collection
     */
    public static function allByTutorAndAdvert(int $tutor, ?int $advert)
    {
        $query = self::from('advert_prices AS p')
            ->select(['p.id', 'p.price_from', 'p.price_type', 'p.minutes', 'c.name'])
            ->join('category AS c', 'c.id', 'p.category_id')
            ->join('adverts AS a', function (JoinClause $join) use ($tutor) {
                $join->on('a.id', 'p.advert_id');
                $join->where('a.profile_id', $tutor);
            });

        if ($advert) {
            $query->where('a.id', $advert);
        }

        return $query->groupBy(['p.id'])
            ->orderBy('c.name')
            ->get()
            ->each(function ($item) {
                $item->name = t($item->name);
                return $item;
            });
    }
}
