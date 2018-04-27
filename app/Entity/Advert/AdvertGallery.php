<?php

namespace App\Entity\Advert;

use Illuminate\Database\Eloquent\Model;

/**
 * @property integer $id
 * @property integer $advert_id
 * @property integer $file_id
 */
class AdvertGallery extends Model
{
    public const MAX_FILES = 20;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = ['advert_id', 'file_id'];

    /**
     * @var string
     */
    public $table = 'advert_gallery';

    /**
     * @var boolean
     */
    public $timestamps = false;
}
