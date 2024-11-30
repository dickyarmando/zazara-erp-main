<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MsProducts extends Model
{
    use HasFactory;

    protected $table = 'ms_products';

    protected $fillable = [
        'name',
        'is_status',
    ];
}
