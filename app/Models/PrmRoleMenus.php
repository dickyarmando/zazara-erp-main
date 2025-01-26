<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PrmRoleMenus extends Model
{
    use HasFactory;

    protected $table = 'prm_role_menus';
    protected $fillable = [
        'role_id',
        'menu_id',
        'is_show',
        'is_create',
        'is_update',
        'is_delete',
        'is_sales',
        'is_approved',
        'is_status',
    ];

    public function menu()
    {
        return $this->belongsTo(PrmMenus::class, 'menu_id');
    }

    public function role()
    {
        return $this->belongsTo(PrmRoles::class, 'role_id');
    }
}
