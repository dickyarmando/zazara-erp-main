<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TrSales extends Model
{
    use HasFactory;

    protected $table = 'tr_sales';
    protected $guarded = ['id'];
}
