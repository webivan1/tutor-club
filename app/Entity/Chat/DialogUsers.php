<?php

namespace App\Entity\Chat;

use App\Entity\User;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Query\Expression;
use Illuminate\Http\Request;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Pagination\Paginator;
use App\Search\Chat\Dialogs as DialogsSearch;

class DialogUsers extends Model
{
    /**
     * @var string
     */
    public $table = 'dialog_users';

    /**
     * @var array
     */
    public $fillable = ['dialog_id', 'user_id', 'visited_at'];

    /**
     * @var boolean
     */
    public $timestamps = false;

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
    public function user()
    {
        return $this->belongsTo(User::class, 'user_id', 'id');
    }

    /**
     * Clear visited_at
     *
     * @param int $dialogId
     * @param int $userId
     * @return void
     */
    public static function up(int $dialogId, int $userId): void
    {
        self::where('dialog_id', $dialogId)
            ->where('user_id', $userId)
            ->update(['visited_at' => null]);
    }

    /**
     * Create date close dialog
     *
     * @param int $dialogId
     * @param int $userId
     * @return void
     */
    public static function down(int $dialogId, int $userId): void
    {
        self::where('dialog_id', $dialogId)
            ->where('user_id', $userId)
            ->update(['visited_at' => now()]);
    }
}
