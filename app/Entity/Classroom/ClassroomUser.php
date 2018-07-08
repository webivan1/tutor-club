<?php

namespace App\Entity\Classroom;

use App\Entity\User;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;

/**
 * @property integer $id
 * @property integer $classroom_id
 * @property integer $user_id
 * @property boolean $tutor
 * @property Carbon $created_at
 * @property Carbon $updated_at
 * @property string $status
 */
class ClassroomUser extends Model
{
    const STATUS_DISABLED = 'disabled';
    const STATUS_ACTIVE = 'active';

    /**
     * @var string
     */
    public $table = 'classroom_users';

    /**
     * @var array
     */
    public $fillable = ['classroom_id', 'user_id', 'tutor', 'status'];

    /**
     * @var array
     */
    public $casts = [
        'tutor' => 'boolean'
    ];

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function classroom()
    {
        return $this->belongsTo(Classroom::class, 'classroom_id', 'id');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function user()
    {
        return $this->belongsTo(User::class, 'user_id', 'id');
    }

    /**
     * @return bool
     */
    public function isTutor(): bool
    {
        return (bool) $this->tutor === true;
    }

    /**
     * @return bool
     */
    public function isActive(): bool
    {
        return (bool) $this->status === self::STATUS_ACTIVE;
    }

    /**
     * @return bool
     */
    public function isDisabled(): bool
    {
        return (bool) $this->status === self::STATUS_DISABLED;
    }
}
