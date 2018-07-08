<?php

namespace App\Entity\Classroom;

use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;

/**
 * @property integer $id
 * @property Carbon $started_at
 * @property Carbon $closed_at
 * @property Carbon $created_at
 * @property Carbon $updated_at
 * @property string $status
 * @property string $comment
 * @property string $subject
 * @property boolean $video
 * @property boolean $audio
 * @property integer $duration
 * @property integer $advert_prices_id
 */
class Classroom extends Model
{
    /**
     * @var string
     */
    public $table = 'classroom';

    /**
     * @var array
     */
    public $fillable = [
        'subject', 'status', 'closed_at', 'started_at',
        'duration', 'video', 'audio', 'comment', 'advert_prices_id'
    ];

    /**
     * @var array
     */
    public $casts = [
        'video' => 'boolean',
        'audio' => 'boolean',
        'closed_at' => 'date',
        'started_at' => 'date'
    ];

    // Статус по дефолту
    const STATUS_PENDING = 'pending';
    // Урок прошел и закрыт
    const STATUS_CLOSED = 'closed';
    // Урок идет
    const STATUS_ACTIVE = 'active';
    // Урок отменен
    const STATUS_CANCEL = 'cancel';

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function users()
    {
        return $this->hasMany(ClassroomUser::class, 'classroom_id', 'id');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasOne
     */
    public function user()
    {
        return $this->hasOne(ClassroomUser::class, 'classroom_id', 'id');
    }

    /**
     * @return mixed
     */
    public function tutor()
    {
        return $this->belongsTo(ClassroomUser::class, 'id', 'classroom_id')
            ->where('tutor', true);
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function messages()
    {
        return $this->hasMany(ClassroomMessage::class, 'id', 'classroom_id');
    }

    /**
     * @return bool
     */
    public function isStarting(): bool
    {
        return $this->started_at->getTimestamp() >= time();
    }

    /**
     * @return bool
     */
    public function isPending(): bool
    {
        return !$this->isStarting() && $this->status === self::STATUS_PENDING;
    }

    /**
     * @return bool
     */
    public function isClosed(): bool
    {
        return $this->isStarting() && $this->status === self::STATUS_CLOSED;
    }

    /**
     * @return bool
     */
    public function isActive(): bool
    {
        return $this->isStarting() && !$this->isClosed() && !$this->isCancel();
    }

    /**
     * @return bool
     */
    public function isCancel(): bool
    {
        return $this->status === self::STATUS_CANCEL;
    }

    /**
     * @return bool
     */
    public function isAccessUser(int $userId): bool
    {
        return $this->users()->where('user_id', $userId)->exists();
    }

    /**
     * Create new classroom
     *
     * @param string $theme
     * @param int $advertCategoryId
     * @param string $publishedAt
     * @param bool $video
     * @return Classroom
     */
    public static function new(string $theme, int $advertCategoryId, string $publishedAt, bool $video): self
    {
        return self::create([
            'subject' => $theme,
            'advert_prices_id' => $advertCategoryId,
            'started_at' => $publishedAt,
            'video' => $video,
            'status' => self::STATUS_PENDING
        ]);
    }
}
