<?php

namespace App\Entity;

use App\Entity\Advert\Advert;
use App\Events\Profile\TutorProfileEvent;
use Illuminate\Database\Eloquent\Model;

/**
 * @property integer $user_id
 */
class TutorProfile extends Model
{
    /**
     * Table name
     *
     * @var string
     */
    protected $table = 'tutor_profile';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'user_id',
        'status',
        'country_code',
        'phone',
        'gender',
        'file_id',
        'phone_verified',
        'phone_token',
        'phone_token_expire',
        'comment'
    ];

    /**
     * @var array
     */
    protected $casts = [
        'phone_verified' => 'boolean'
    ];

    /**
     * @var string
     */
    private $methodSave;

    public const STATUS_DISABLED = 'disabled';
    public const STATUS_MODERATION = 'moderation';
    public const STATUS_ACTIVE = 'active';
    public const STATUS_PENDING = 'pending';
    public const STATUS_BLOCKED = 'blocked';

    public const MAN = 'man';
    public const WOMAN = 'woman';

    public const METHOD_SAVE = 'save';
    public const METHOD_MODERATION = 'moderation';

    /**
     * @param string $method
     */
    public function setMethodSave(string $method): void
    {
        $this->methodSave = $method;
    }

    /**
     * @return null|string
     */
    public function getMethodSave(): ?string
    {
        return $this->methodSave;
    }

    /**
     * @return bool
     */
    public function isDisabled(): bool
    {
        return $this->status === self::STATUS_DISABLED;
    }

    /**
     * @return bool
     */
    public function isModeration(): bool
    {
        return $this->status === self::STATUS_MODERATION;
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
    public function isPending(): bool
    {
        return $this->status === self::STATUS_PENDING;
    }

    /**
     * @return bool
     */
    public function isBlocked(): bool
    {
        return $this->status === self::STATUS_BLOCKED;
    }

    /**
     * @return bool
     */
    public function isVerified(): bool
    {
        return (bool) $this->phone_verified;
    }

    /**
     * @return bool
     */
    public function isMethodSave(): bool
    {
        return $this->getMethodSave() === self::METHOD_SAVE;
    }

    /**
     * @return bool
     */
    public function isMethodModeration(): bool
    {
        return $this->getMethodSave() === self::METHOD_MODERATION;
    }

    /**
     * @param $country_code
     * @param $phone
     * @return bool
     */
    public function isChangePhone($country_code, $phone): bool
    {
        $model = self::where('user_id', \Auth::id())
            ->where('country_code', $country_code)
            ->where('phone', $phone)
            ->first();

        return empty($model);
    }

    /**
     * @return TutorProfile
     */
    public function clearStatus(): self
    {
        $this->status = self::STATUS_DISABLED;
        return $this;
    }

    /**
     * @return TutorProfile
     */
    public function moderate(): self
    {
        $this->status = self::STATUS_MODERATION;
        return $this;
    }

    /**
     * @return void
     */
    public function clearVerifyPhone(): void
    {
        $this->phone_verified = false;
        $this->phone_token = null;
        $this->phone_token_expire = null;
    }

    /**
     * @return TutorProfile
     */
    public function generateTokenVerified(): self
    {
        $this->phone_verified = false;
        $this->phone_token = random_int(10000, 99999);
        $this->phone_token_expire = (new \DateTime)->modify('+ 5 minutes')->format('Y-m-d H:i:s');
        return $this;
    }

    /**
     * @return bool
     */
    public function isTokenExpired(): bool
    {
        return !$this->phone_token_expire || strtotime($this->phone_token_expire) < time();
    }

    /**
     * @return string
     */
    public function getFullPhone(): string
    {
        return $this->country_code . $this->phone;
    }

    /**
     * @return array
     */
    public function statuses(): array
    {
        return [
            self::STATUS_ACTIVE => 'Активен',
            self::STATUS_PENDING => 'Требуется доработка',
            self::STATUS_BLOCKED => 'Заблокирован',
            self::STATUS_MODERATION => 'Проверяется модератором',
            self::STATUS_DISABLED => 'Профиль не активен'
        ];
    }

    /**
     * @return array
     */
    public static function genders(): array
    {
        return [
            self::MAN => t('Man'),
            self::WOMAN => t('Woman')
        ];
    }

    /**
     * @return array
     */
    public function methods(): array
    {
        return [
            self::METHOD_SAVE,
            self::METHOD_MODERATION
        ];
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasOne
     */
    public function user()
    {
        return $this->hasOne(User::class, 'id', 'user_id');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function content()
    {
        return $this->hasMany(ContentProfile::class, 'profile_id', 'id');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function adverts()
    {
        return $this->hasMany(Advert::class, 'profile_id', 'id');
    }

    /**
     * @return TutorProfile|null
     */
    public static function findModel(): ?self
    {
        static $model = false;
        return $model = $model === false
            ? self::where('user_id', \Auth::id())->first()
            : $model;
    }

    /**
     * @param string $type
     * @param string $lang
     * @return null|string
     */
    public function getText(string $type, string $lang): ?string
    {
        if (!$this->content) {
            return null;
        }

        foreach ($this->content as $item) {
            if ($item->lang === $lang) {
                return $item->{$type};
            }
        }

        return null;
    }

    /**
     * @return void
     */
    public function successVerifyPhone(): void
    {
        $this->status = self::STATUS_DISABLED;
        $this->phone_verified = true;
        $this->save();
    }
}
