<?php

namespace App\Entity\Media;

use App\Entity\Files;
use Illuminate\Database\Eloquent\Model;

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
        'heading', 'slug', 'category_id', 'content', 'file_id', 'title', 'description', 'lang', 'status', 'published_at'
    ];

    public const STATUS_DRAFT = 'draft';
    public const STATUS_ACTIVE = 'active';
    public const STATUS_DISABLED = 'disabled';

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
        return $this->status === self::STATUS_ACTIVE;
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
}
