<?php

namespace App\Entity;

use Illuminate\Database\Eloquent\Model;

class UserProvider extends Model
{
    public $fillable = ['user_id', 'source', 'source_id', 'email', ''];
    public $table = 'user_providers';
    public $casts = [
        'verify_email' => 'boolean'
    ];
}
