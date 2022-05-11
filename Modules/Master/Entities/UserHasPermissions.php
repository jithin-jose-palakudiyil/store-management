<?php

namespace Modules\Master\Entities;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class UserHasPermissions extends Model
{
    protected $table = "user_has_permissions";  
    protected $fillable = ['permission_id','user_id'];
}
