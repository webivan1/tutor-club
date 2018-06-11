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
 */
class ClassroomUser extends Model
{
    /**
     * @var string
     */
    protected $table = 'classroom_users';

    /**
     * @var array
     */
    protected $fillable = ['classroom_id', 'user_id', 'tutor'];

    /**
     * @var array
     */
    protected $casts = [
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
}
