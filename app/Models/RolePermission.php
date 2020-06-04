<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class RolePermission extends Model
{
    protected $table = 'roles_permissions';

    protected $fillable = ['role_id', 'permission_id', 'entity'];

    public function permission() {
        return $this->hasOne(Permission::class, 'id', 'permission_id');
    }
}
