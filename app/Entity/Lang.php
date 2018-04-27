<?php

namespace App\Entity;

use Illuminate\Database\Eloquent\Model;

class Lang extends Model
{
    /**
     * @var string
     */
    public $table = 'languages';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name',
        'native',
        'value',
        'regional'
    ];

    /**
     * All words by lang
     */
    public function words()
    {
        return $this->hasMany(Words::class, 'language_id', 'id');
    }
}
