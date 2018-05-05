<?php

namespace App\Entity;

use Illuminate\Database\Eloquent\Model;

class CategoryCounts extends Model
{
    /**
     * @var string
     */
    public $table = 'category_counts';

    /**
     * @var array
     */
    protected $fillable = [
        'category_id', 'lang', 'total_categories', 'total_adverts'
    ];

    /**
     * @var bool
     */
    public $timestamps = false;
}
