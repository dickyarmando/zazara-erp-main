<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TrSalesNonDetails extends Model
{
    use HasFactory;

    protected $table = 'tr_sales_non_details';
    protected $guarded = ['id'];
}
