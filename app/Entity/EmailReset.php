<?php

namespace App\Entity;

use App\Events\Profile\EmailResetEvent;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

/**
 * @property string $email
 * @property string $token
 */
class EmailReset extends Model
{
    public const EXPIRE_TOKEN = 3600; // 1 hour

    /**
     * @var string
     */
    protected $table = 'email_reset';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = ['email', 'token', 'user_id'];

    /**
     * {@inheritdoc}
     */
    public static function boot()
    {
        self::observe(new EmailResetEvent());
        parent::boot();
    }

    /**
     * Create new reset email
     *
     * @param string $email
     * @param int $userId
     * @return void
     */
    public static function addEmail(string $email, int $userId): void
    {
        self::create([
            'email' => $email,
            'user_id' => $userId,
            'token' => Str::uuid(),
        ]);
    }

    /**
     * @return bool
     */
    public function isActiveToken(): bool
    {
        return (time() - strtotime($this->created_at)) <= self::EXPIRE_TOKEN;
    }
}
