<?php

namespace App\Entity\Advert;

use App\Entity\Attribute;
use App\Entity\Category;
use App\Entity\Files;
use App\Entity\TutorProfile;
use App\Entity\User;
use App\Services\ElasticSearch\ModelSearch;
use Chelout\RelationshipEvents\Concerns\HasManyEvents;
use Chelout\RelationshipEvents\Concerns\HasOneEvents;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Query\JoinClause;
use Illuminate\Support\Carbon;

/**
 * @property integer $user_id
 * @property integer $profile_id
 * @property string $description
 * @property string $title
 * @property string $lang
 * @property string $status
 * @property string $presentation Презентация, видеобращение
 * @property boolean $test Бесплатный пробный урок
 * @property integer $experience Опыт (лет)
 * @property Carbon $updated_at
 * @property Carbon $created_at
 */
class Advert extends Model implements ModelSearch
{
    use AdvertSearchTrait;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'user_id', 'profile_id', 'title', 'description', 'lang',
        'status', 'presentation', 'experience'
    ];

    /**
     * @var string
     */
    public $table = 'adverts';

    /**
     * @var array
     */
    public $casts = [
        'test' => 'boolean'
    ];

    public const STATUS_DRAFT = 'draft';
    public const STATUS_WAIT = 'wait';
    public const STATUS_MODERATION = 'moderation';
    public const STATUS_REJECT = 'reject';
    public const STATUS_ACTIVE = 'active';
    public const STATUS_DISABLED = 'disabled';

    /**
     * @return array
     */
    public static function statuses(): array
    {
        return [
            self::STATUS_DRAFT => t('Draft'),
            self::STATUS_DISABLED => t('Closed'),
            self::STATUS_MODERATION => t('Moderation'),
            self::STATUS_ACTIVE => t('Active'),
            self::STATUS_WAIT => t('Waiting'),
            self::STATUS_REJECT => t('Blocked')
        ];
    }

    /**
     * @return array
     */
    public static function statusStyles(): array
    {
        return [
            self::STATUS_DRAFT => 'secondary',
            self::STATUS_DISABLED => 'dark',
            self::STATUS_MODERATION => 'warning',
            self::STATUS_ACTIVE => 'success',
            self::STATUS_WAIT => 'dark',
            self::STATUS_REJECT => 'danger'
        ];
    }

    /**
     * @return bool
     */
    public function isDraft(): bool
    {
        return $this->status === self::STATUS_DRAFT;
    }

    /**
     * @return bool
     */
    public function isWait(): bool
    {
        return $this->status === self::STATUS_WAIT;
    }

    /**
     * @return bool
     */
    public function isDisabled(): bool
    {
        return $this->status === self::STATUS_DISABLED;
    }

    /**
     * @return bool
     */
    public function isModeration(): bool
    {
        return $this->status === self::STATUS_MODERATION;
    }

    /**
     * @return bool
     */
    public function isActive(): bool
    {
        return $this->status === self::STATUS_ACTIVE;
    }

    /**
     * @return bool
     */
    public function isReject(): bool
    {
        return $this->status === self::STATUS_REJECT;
    }

    /**
     * @return bool
     */
    public function isEdit(): bool
    {
        return $this->isActive() ||
            $this->isDisabled() ||
            $this->isDraft() ||
            $this->isWait();
    }

    /**
     * @return bool
     */
    public function accessSendToModeration(): bool
    {
        return $this->isDraft() || $this->isWait() || $this->isDisabled();
    }

    /**
     * To status draft
     *
     * @return void
     */
    public function toStatusDraft(): void
    {
        if ($this->isActive()) {
            $this->status = self::STATUS_DRAFT;
            $this->save();
        }
    }

    /**
     * To status draft
     *
     * @return void
     */
    public function toStatusDisabled(): void
    {
        if ($this->isActive()) {
            $this->status = self::STATUS_DISABLED;
            $this->save();
        }
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function prices()
    {
        return $this->hasMany(AdvertPrice::class, 'advert_id', 'id');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function user()
    {
        return $this->belongsTo(User::class, 'user_id', 'id');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function profile()
    {
        return $this->belongsTo(TutorProfile::class, 'profile_id', 'id');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
     */
    public function files()
    {
        return $this->belongsToMany(Files::class, 'advert_gallery', 'advert_id', 'file_id');
    }

    /**
     * @return mixed
     */
    public function avatar()
    {
        return $this->hasOne(Files::class, 'source_id', 'user_id')
            ->where('source', 'user');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasManyThrough
     */
    public function category()
    {
        return $this->hasManyThrough(Category::class, AdvertPrice::class, 'advert_id', 'id', null, 'category_id');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function attributeValues()
    {
        return $this->hasMany(AdvertAttribute::class, 'advert_id', 'id');
    }

    /**
     * @var array
     */
    private static $allAttributes = [];

    /**
     * @return Advert
     */
    public function clearAllAttributes(): self
    {
        self::$allAttributes = [];
        return $this;
    }

    /**
     * All attributes with value
     *
     * @return array
     */
    public function getAllAttributes(): array
    {
        if (!empty(self::$allAttributes)) {
            return self::$allAttributes;
        }

        /** @var Category $category */
        foreach ($this->category ?? [] as $category) {
            /** @var Attribute[] $attributes */
            $attributes = $category->allAttributes(function (HasMany $builder): array {
                return $builder
                    ->select(['attributes.*', 'advert_attribute.value AS value'])
                    ->leftJoin('advert_attribute', function (JoinClause $join) {
                        return $join->on('advert_attribute.attribute_id', 'attributes.id')
                            ->where('advert_attribute.advert_id', $this->id);
                    })
                    ->groupBy('attributes.id', 'advert_attribute.value')
                    ->getModels();
            });

            foreach ($attributes as $attribute) {
                if (!array_key_exists($attribute->id, self::$allAttributes)) {
                    self::$allAttributes[$attribute->id] = $attribute;
                }
            }
        }

        return self::$allAttributes;
    }

    /**
     * Root category
     *
     * @return Category
     */
    public function getRootCategory(): Category
    {
        /** @var Category $category */
        $category = $this->category()->firstOrFail();

        $rootCategory = Category::where($category->getLftName(), '<', $category->getLft())
            ->where($category->getRgtName(), '>', $category->getRgt())
            ->whereNull('parent_id')
            ->firstOrFail();

        return $rootCategory;
    }

    /**
     * Update updated_at
     *
     * @return void
     */
    public function updateCurrentTimestamp(): void
    {
        $this->setUpdatedAt($this->freshTimestamp());
        $this->save();
    }
}
