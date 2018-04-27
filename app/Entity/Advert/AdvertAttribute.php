<?php

namespace App\Entity\Advert;

use App\Entity\Attribute;
use Illuminate\Database\Eloquent\Model;

/**
 * @property integer $id
 * @property integer $advert_id
 * @property integer $attribute_id
 * @property string $value
 */
class AdvertAttribute extends Model
{
    /**
     * @var string
     */
    protected $table = 'advert_attribute';

    /**
     * @var array
     */
    protected $fillable = ['advert_id', 'attribute_id', 'value'];

    /**
     * @var bool
     */
    public $timestamps = false;

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function advert()
    {
        return $this->belongsTo(Advert::class, 'advert_id', 'id');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function attribute()
    {
        return $this->belongsTo(Attribute::class, 'attribute_id', 'id');
    }
}
