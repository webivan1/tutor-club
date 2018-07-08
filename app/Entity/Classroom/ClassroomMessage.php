<?php

namespace App\Entity\Classroom;

use App\Entity\User;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;

/**
 * @property integer $id
 * @property integer $classroom_id
 * @property integer $user_id
 * @property string $message
 * @property Carbon $created_at
 * @property Carbon $updated_at
 */
class ClassroomMessage extends Model
{
    /**
     * @var string
     */
    public $table = 'classroom_messages';

    /**
     * @var array
     */
    public $fillable = ['classroom_id', 'user_id', 'message'];

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
    public function classroom()
    {
        return $this->belongsTo(Classroom::class, 'classroom_id', 'id');
    }

    /**
     * @param int $classroomId
     * @param int $userId
     * @return bool
     */
    public static function isTutor(int $classroomId, int $userId): bool
    {
        $model = ClassroomUser::where('classroom_id', $classroomId)
            ->where('user_id', $userId)
            ->first();

        return $model->isTutor();
    }


    /**
     * Get the message.
     *
     * @param string $value
     * @return string
     */
    public function getMessageAttribute($value)
    {
        return clean($value);
    }

    /**
     * List messages
     *
     * @param Classroom $classroom
     * @return mixed
     */
    public static function listData(Classroom $classroom)
    {
        $result = self::where('classroom_id', $classroom->id)
            ->with(['classroom', 'user'])
            ->orderByDesc('created_at')
            ->paginate(30)
            ->toArray();

        if ($result['total'] > 0) {
            $result['data'] = array_map(function ($item) {
                return array_merge($item, [
                    'label' => User::getLabelName($item['user']['name']),
                    'isTutor' => self::isTutor($item['classroom_id'], $item['user_id'])
                ]);
            }, array_reverse($result['data']));
        }

        return $result;
    }

    /**
     * Add new message
     *
     * @param string $message
     * @param Classroom $classroom
     * @param User $user
     * @return array
     */
    public static function add(string $message, Classroom $classroom, User $user)
    {
        /** @var ClassroomMessage $model */
        $model = self::create([
            'message' => $message,
            'classroom_id' => $classroom->id,
            'user_id' => $user->id
        ]);

        if (!$model) {
            throw new \DomainException(t('Error insert new message'));
        }

        return array_merge($model->toArray(), [
            'classroom' => $classroom->toArray(),
            'user' => $user->toArray(),
            'label' => User::getLabelName($user->name),
            'isTutor' => self::isTutor($classroom->id, $user->id)
        ]);
    }
}
