<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PrmConfig extends Model
{
    use HasFactory;

    protected $table = 'prm_configs';

    protected $fillable = [
        'code',
        'name',
        'type',
        'value',
        'is_status',
    ];
}
