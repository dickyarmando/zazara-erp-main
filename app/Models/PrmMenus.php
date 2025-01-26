<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PrmMenus extends Model
{
    use HasFactory;

    protected $table = 'prm_menus';

    protected $fillable = [
        'parent_id',
        'name',
        'icon',
        'action',
        'seq',
        'is_show',
        'is_create',
        'is_update',
        'is_delete',
        'is_status',
    ];

    public function childs()
    {
        return $this->hasMany('App\Models\PrmMenus', 'parent_id', 'id')->where('prm_menus.is_status', '=', '1')->orderBy('prm_menus.seq', 'asc');
    }
}
