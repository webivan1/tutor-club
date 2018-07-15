<?php

namespace App\Entity\Classroom;

use App\Entity\Advert\AdvertPrice;
use App\Entity\TutorProfile;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;
use Illuminate\Database\Query\Expression;

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
 * @property float $price
 * @property integer $minutes
 * @property integer $tutor_id
 * @property string $price_type
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
        'duration', 'video', 'audio', 'comment', 'advert_prices_id',
        'tutor_id', 'minutes', 'price', 'price_type'
    ];

    /**
     * @var array
     */
    public $casts = [
        'video' => 'boolean',
        'audio' => 'boolean',
    ];

    /**
     * The attributes that should be mutated to dates.
     *
     * @var array
     */
    protected $dates = [
        'created_at',
        'updated_at',
        'closed_at',
        'started_at'
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
     * @return array
     */
    public static function statuses(): array
    {
        return [
            self::STATUS_PENDING => t('Pending'),
            self::STATUS_CLOSED => t('Closed'),
            self::STATUS_CANCEL => t('Disabled'),
            self::STATUS_ACTIVE => t('Active')
        ];
    }

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
     * @return mixed
     */
    public function tutorModel()
    {
        return $this->belongsTo(TutorProfile::class, 'tutor_id', 'id');
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
        return $this->started_at->getTimestamp() <= time();
    }

    /**
     * @return bool
     */
    public function isPending(): bool
    {
        return !$this->isStarting() && ($this->status === self::STATUS_PENDING || $this->isActiveStatus());
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
        return $this->isStarting() && $this->isActiveStatus();
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
    public function isActiveStatus(): bool
    {
        return $this->status === self::STATUS_ACTIVE;
    }

    /**
     * @return bool
     */
    public function isPendingStatus(): bool
    {
        return $this->status === self::STATUS_PENDING;
    }

    /**
     * @return bool
     */
    public function isAccessUser(int $userId): bool
    {
        return $this->users()->where('user_id', $userId)->exists();
    }

    /**
     * @return bool
     */
    public function isViewRoom(): bool
    {
        return $this->isActive() || $this->isClosed();
    }

    /**
     * @return bool
     */
    public function canClose(): bool
    {
        return $this->started_at->subHours(6)->getTimestamp() >= time();
    }

    /**
     * Create new classroom
     *
     * @param AdvertPrice $theme
     * @param string $publishedAt
     * @param bool $video
     * @return Classroom
     */
    public static function new(AdvertPrice $theme, string $publishedAt, bool $video): self
    {
        return self::create([
            'subject' => t($theme->category->name),
            'advert_prices_id' => $theme->id,
            'price' => $theme->price_from,
            'price_type' => $theme->price_type,
            'tutor_id' => $theme->advert->profile_id,
            'minutes' => $theme->minutes,
            'started_at' => $publishedAt,
            'video' => $video,
            'status' => self::STATUS_PENDING
        ]);
    }

    /**
     * @param Builder $builder
     */
    public function scopeIsNotStarted(Builder $builder)
    {
        $builder->where('started_at', '>', new Expression('NOW()'))
            ->whereIn('status', [self::STATUS_ACTIVE, self::STATUS_PENDING]);
    }

    /**
     * @param TutorProfile $profile
     * @return \Illuminate\Database\Eloquent\Collection|static[]
     */
    public static function getListByTutor(TutorProfile $profile)
    {
        return self::where('tutor_id', $profile->id)->isNotStarted()->get();
    }

    /**
     * @param int $tutorId
     * @return bool
     */
    public function hasTutor(int $tutorId): bool
    {
        return (int) $this->tutor_id === $tutorId;
    }
}
