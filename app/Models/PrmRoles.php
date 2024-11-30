<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PrmRoles extends Model
{
    use HasFactory;

    protected $table = 'prm_roles';

    protected $fillable = [
        'name',
        'is_status',
        'created_by',
        'updated_by',
    ];

    public function roleMenus()
    {
        return $this->hasMany(PrmRoleMenus::class, 'role_id');
    }
}
