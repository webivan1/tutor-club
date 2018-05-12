<?php

namespace App\Entity;

use Illuminate\Database\Eloquent\Model;

class UserProvider extends Model
{
    public $fillable = [
        'user_id', 'source', 'source_id', 'email', 'provider_data', 'key_code', 'verify_email'
    ];
    public $table = 'user_providers';
    public $casts = [
        'verify_email' => 'boolean'
    ];

    public function isVerify(): bool
    {
        return !empty($this->user_id) && (bool) $this->verify_email;
    }

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id', 'id');
    }
}
