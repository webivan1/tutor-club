<?php

namespace App\Entity;

use App\Events\Profile\ContentProfileEvent;
use Illuminate\Database\Eloquent\Model;

class ContentProfile extends Model
{
    /**
     * @var string
     */
    public $table = 'content_profile';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'profile_id',
        'description',
        'content',
        'lang',
    ];

    public static function boot()
    {
        self::observe(new ContentProfileEvent());
        parent::boot(); // TODO: Change the autogenerated stub
    }
}
