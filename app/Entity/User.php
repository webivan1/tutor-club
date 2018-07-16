<?php

namespace App\Entity;

use App\Entity\Admin\RoleHasUser;
use App\Entity\Classroom\Classroom;
use App\Entity\Classroom\ClassroomUser;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Notifications\Notifiable;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Silber\Bouncer\Database\HasRolesAndAbilities;
use App\Entity\Admin\Role;
use Illuminate\Support\Str;

/**
 * Class User
 * @package App\Entity
 *
 * @property string $password
 * @property string $name
 * @property string $email
 * @property string $status
 * @property string $verify_token
 * @property string $created_at
 * @property string $updated_at
 * @property integer $id
 * @property string $timezone
 */
class User extends Authenticatable
{
    use Notifiable, HasRolesAndAbilities, AccessRolesTrait;

    public const STATUS_WAIT = 'wait';
    public const STATUS_ACTIVE = 'active';
    public const STATUS_BANNED = 'banned';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name', 'email', 'password', 'status', 'verify_token', 'active_at', 'timezone'
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password', 'remember_token',
    ];

    /**
     * @var string
     */
    private $originPassword;

    /**
     * @var Role
     */
    private $role;

    /**
     * Relation with role
     *
     * @return Builder
     */
    public function roleUser()
    {
        return $this->hasOne(RoleHasUser::class, 'entity_id', 'id')
            ->where('entity_type', $this->getMorphClass());
    }

    /**
     * Relation with file
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function image()
    {
        return $this->belongsTo(Files::class, 'file_id', 'id');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function tutor()
    {
        return $this->belongsTo(TutorProfile::class, 'id', 'user_id')
            ->where('status', TutorProfile::STATUS_ACTIVE);
    }

    /**
     * Get the class name for polymorphic relations.
     *
     * @return string
     */
    public function getMorphClass()
    {
        return 'user'; // entity_type
    }

    /**
     * @return string|null
     */
    public function getOriginPassword(): ?string
    {
        return $this->originPassword;
    }

    /**
     * @param string $originPassword
     */
    public function setOriginPassword(?string $originPassword)
    {
        $this->originPassword = $originPassword;
    }

    /**
     * @return Role|null
     */
    public function getRole(): ?Role
    {
        return $this->role;
    }

    /**
     * @param Role $role
     */
    public function setRole(Role $role)
    {
        $this->role = $role;
    }

    /**
     * @param string $name
     * @param string $email
     * @param string $password
     * @param string|null $status
     * @return User
     */
    public static function register(string $name, string $email, string $password, string $status = null): self
    {
        return static::create([
            'name' => $name,
            'email' => $email,
            'password' => $password,
            'status' => !$status ? self::STATUS_WAIT : $status,
            'verify_token' => Str::random(23)
        ]);
    }

    /**
     * @return void
     */
    public function verify(): void
    {
        if (!$this->isWait()) {
            throw new \DomainException(trans('auth.error_user_not_is_wait'));
        }

        $this->update([
            'verify_token' => null,
            'status' => self::STATUS_ACTIVE
        ]);
    }

    /**
     * @return bool
     */
    public function isWait(): bool
    {
        return $this->status === self::STATUS_WAIT;
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
    public function isBanned(): bool
    {
        return $this->status === self::STATUS_BANNED;
    }

    /**
     * @return bool
     */
    public function isOnline(): bool
    {
        return strtotime($this->active_at) >= time();
    }

    /**
     * @return void
     */
    public function onlineUpdate(): void
    {
        $this->active_at = (new Carbon())->addMinutes(15);
        $this->save();
    }

    /**
     * Find use by token
     *
     * @param string|null $token
     * @throws \Exception
     * @return User
     */
    public static function findUserByToken(?string $token = null): User
    {
        $model = self::where('verify_token', $token)
            ->where('status', User::STATUS_WAIT)
            ->first();

        if (empty($model)) {
            throw new \DomainException(trans('auth.token_exist_error'));
        }

        return $model;
    }

    /**
     * Default role
     *
     * @return Role|null
     */
    public function defaultRole(): ?Role
    {
        return Role::orderByDesc('level')->first() ?? null;
    }

    /**
     * Change email
     *
     * @param string $email
     * @return bool
     */
    public function changeEmail(string $email): bool
    {
        $this->email = $email;
        return $this->save();
    }

    /**
     * Change password
     *
     * @param string $password
     * @return bool
     */
    public function changePassword(string $password) : bool
    {
        $this->setOriginPassword($password);
        $this->password = bcrypt($password);
        return $this->save();
    }

    /**
     * @param string $username
     * @return string
     */
    public static function getLabelName(string $username): string
    {
        $username = preg_replace('/[^\A-zА-я0-9\s]/ui', '', $username);

        $usernameArr = explode(' ', $username);
        $usernameArr = array_map(function ($str) use ($usernameArr) {
            return mb_strtoupper(mb_substr($str, 0, count($usernameArr) > 1 ? 1 : 2, 'UTF8'), 'UTF8');
        }, array_slice($usernameArr, 0, 2));

        return implode('', $usernameArr);
    }

    /**
     * Выводим дату с временной зоны пользователя
     *
     * @param null|string $date
     * @return Carbon
     */
    public function getDate(?string $date = null): Carbon
    {
        return new Carbon($date, !$this->timezone ? null : new \DateTimeZone($this->timezone));
    }
}
