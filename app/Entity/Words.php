<?php

namespace App\Entity;

use Illuminate\Database\Eloquent\Model;

class Words extends Model
{
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = ['lang', 'word_key_id', 'translate'];
}
