<?php

namespace App\Entity\Media;

use App\Entity\Files;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Query\Expression;
use Carbon\Carbon;

/**
 * @property Files $image
 * @property Carbon $published_at
 * @property string $status
 * @property Category $category
 *
 * @method listData()
 */
class News extends Model
{
    /**
     * @var string
     */
    public $table = 'news';

    /**
     * @var array
     */
    protected $fillable = [
        'heading', 'slug', 'category_id', 'content', 'file_id', 'title',
        'description', 'lang', 'status', 'published_at'
    ];

    /**
     * @var array
     */
    public $casts = [
        'published_at' => 'date'
    ];

    public const STATUS_DRAFT = 'draft';
    public const STATUS_ACTIVE = 'active';
    public const STATUS_DISABLED = 'disabled';

    protected static function boot()
    {
        parent::boot();

        static::deleting(function (News $news) {
            !$news->image ?: $news->image->delete();
        });
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
    public function isActive(): bool
    {
        return $this->status === self::STATUS_ACTIVE
            && $this->published_at->getTimestamp() <= Carbon::now()->getTimestamp();
    }

    /**
     * @return bool
     */
    public function isLang(): bool
    {
        return $this->lang === app()->getLocale();
    }

    /**
     * @return bool
     */
    public function isDisabled(): bool
    {
        return $this->status === self::STATUS_DISABLED;
    }

    /**
     * @return array
     */
    public static function statusesLabels(): array
    {
        return [
            self::STATUS_ACTIVE => 'Активен',
            self::STATUS_DISABLED => 'Отключен',
            self::STATUS_DRAFT => 'Черновик',
        ];
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasOne
     */
    public function image()
    {
        return $this->hasOne(Files::class, 'id', 'file_id');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function category()
    {
        return $this->belongsTo(Category::class);
    }

    /**
     * Get the route key for the model.
     *
     * @return string
     */
    public function getRouteKeyName()
    {
        return 'slug';
    }

    /**
     * News list
     *
     * @param Builder $builder
     */
    public function scopelistData(Builder $builder)
    {
        $builder->where('status', self::STATUS_ACTIVE)
            ->where('published_at', '<=', new Expression('NOW()'))
            ->where('lang', app()->getLocale())
            ->orderBy('published_at', 'desc');
    }
}
