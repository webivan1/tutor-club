<?php

namespace App\Entity\Admin;

use Illuminate\Database\Eloquent\Model;

class PermissionHasRole extends Model
{
    /**
     * @var string
     */
    public $table = 'permissions';
}
