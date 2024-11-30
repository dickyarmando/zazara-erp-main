<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TrSalesDetails extends Model
{
    use HasFactory;

    protected $table = 'tr_sales_details';
    protected $guarded = ['id'];
}
