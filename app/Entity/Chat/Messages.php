<?php

namespace App\Entity\Chat;

use App\Entity\Classroom\Classroom;
use App\Entity\Classroom\ClassroomUser;
use App\Entity\User;
use App\Events\Chat\CreateMessage;
use App\Events\Chat\MessageEvent;
use App\Events\Chat\SendMessage;
use App\Events\Chat\SendMessageArray;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Query\Expression;

class Messages extends Model
{
    const QUEUE_NAME = 'messages';

    /**
     * @var string
     */
    public $table = 'messages';

    /**
     * @var array
     */
    public $fillable = ['message', 'dialog_id', 'user_id', 'created_at', 'classroom_id'];

    /**
     * {@inheritdoc}
     */
    public static function boot()
    {
        parent::boot();

        self::observe(new MessageEvent());
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function dialog()
    {
        return $this->belongsTo(Dialogs::class, 'dialog_id', 'id');
    }

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
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function users()
    {
        return $this->hasMany(DialogUsers::class, 'dialog_id', 'dialog_id');
    }

    /**
     * @param string $value
     * @return string
     */
    public function getMessageAttribute(string $value)
    {
        return clean($value);
    }

    /**
     * Create new message
     *
     * @param string $message
     * @param int $dialogId
     * @param int $userId
     * @return Messages
     */
    public static function new(string $message, int $dialogId, int $userId): Messages
    {
        return self::create([
            'message' => $message,
            'dialog_id' => $dialogId,
            'user_id' => $userId
        ]);
    }

    /**
     * Create new invite lesson
     *
     * @param int $dialogId
     * @param int $userId
     * @param Classroom $classroom
     * @return Messages
     */
    public static function newInvite(int $dialogId, int $userId, Classroom $classroom): Messages
    {
        $message = "{$classroom->subject} <b>{$classroom->price} {$classroom->price_type}</b> / <b>{$classroom->minutes}</b> min";

        return self::create([
            'message' => $message,
            'dialog_id' => $dialogId,
            'user_id' => $userId,
            'classroom_id' => $classroom->id
        ]);
    }

    /**
     * Query builder for chat
     *
     * @param Builder $builder
     * @param int|null $dialogId
     * @param int|null $id
     * @return Builder
     */
    public function scopeWithUser(Builder $builder, ?int $dialogId, ?int $id = null): Builder
    {
        !$dialogId ?: $builder->where('dialog_id', $dialogId);
        !$id ?: $builder->where('id', $id);

        return $builder->with([
            'user' => function ($builder) {
                $builder->select(['id', 'name']);
            },
            'classroom' => function ($builder) {
                $builder->select(['id', 'status', 'started_at', 'subject', 'price', 'minutes'])
                    ->isNotStarted()
                    ->with(['users' => function ($builder) {
                        $builder->where('status', ClassroomUser::STATUS_DISABLED);
                    }]);
            }
        ])->orderByDesc('created_at');
    }

    /**
     * List messages
     *
     * @param int $dialogId
     * @param int $perPage
     * @return array
     */
    public function listData(int $dialogId, int $perPage): array
    {
        $result = self::withUser($dialogId)
            ->paginate($perPage)
            ->toArray();

        if ($result['total'] > 0) {
            $result['data'] = array_reverse($result['data']);
        }

        return $result;
    }

    /**
     * Item message by id
     *
     * @param int $id
     * @return array
     */
    public function getItem(int $id): array
    {
        return self::withUser(null, $id)->first()->toArray();
    }

    /**
     * Create model message for chat
     *
     * @param array $sourceDialog
     * @param string $message
     * @param int $fromUser
     * @return array
     */
    public function createModelBySourceDialog(array $sourceDialog, string $message, int $fromUser): array
    {
        // Создаем модель на основе данных
        $model = self::make([
            'created_at' => now()->format('Y-m-d H:i:s'),
            'message' => $message,
            'user_id' => $fromUser,
            'dialog_id' => $sourceDialog['id'],
        ]);

        $name = null;
        $users = [];

        // Заполняем юзера из данных диалога
        if (!empty($sourceDialog['users'])) {
            foreach ($sourceDialog['users'] as $user) {
                if ((int) $user['user_id'] === $fromUser) {
                    $name = $user['user']['name'];
                } else {
                    array_push($users, (int) $user['user_id']);
                }
            }
        } else {
            $name = User::where('id', $fromUser)->first()->name;
        }

        $messageResult = array_merge($model->toArray(), [
            'user' => [
                'id' => $fromUser,
                'name' => $name
            ]
        ]);

        // Отправляем всем в чат
        event(
            (new SendMessageArray($messageResult, $users))->onQueue(self::QUEUE_NAME)
        );

        // Создаем в базе
        $model->save();

        return $messageResult;
    }
}
