<?php

namespace App\Entity\Admin;

use Illuminate\Database\Eloquent\Model;

class RoleHasUser extends Model
{
    /**
     * @var string
     */
    public $table = 'assigned_roles';
}
