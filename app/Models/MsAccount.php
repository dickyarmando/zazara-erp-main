<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MsAccount extends Model
{
    use HasFactory;

    protected $table = 'ms_accounts';
    protected $guarded = ['id'];
}
