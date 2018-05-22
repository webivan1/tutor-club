<?php

namespace App\Entity;

use Illuminate\Database\Eloquent\Model;

class Keywords extends Model
{
    /**
     * @var string
     */
    public $table = 'word_key';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = ['name'];

    /**
     * All words by key
     */
    public function words()
    {
        return $this->hasMany(Words::class, 'word_key_id', 'id');
    }

    /**
     * All word by key
     */
    public function word()
    {
        return $this->hasOne(Words::class, 'word_key_id', 'id');
    }

    /**
     * Вставляем недостующие ключи в базу для перевода
     *
     * @param array $keys
     */
    public static function syncKeys(array $keys): void
    {
        if (empty($keys)) {
            return;
        }

        $keys = array_unique($keys);

        info('Translate new keys ' . json_encode($keys));

        $existKeys = self::whereIn('name', $keys)->pluck('name')->toArray();

        if (!empty($existKeys)) {
            $keys = array_diff($keys, $existKeys);

            if (empty($keys)) {
                return;
            }
        }

        foreach ($keys as $key) {
            try {
                self::firstOrCreate(['name' => $key]);
            } catch (\Exception $e) {
                info('Double key ' . $key);
            }
        }
    }
}