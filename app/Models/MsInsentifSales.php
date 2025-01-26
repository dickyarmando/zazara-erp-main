<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MsInsentifSales extends Model
{
    use HasFactory;

    protected $table = 'ms_insentif_sales';
    protected $guarded = ['id'];
}
