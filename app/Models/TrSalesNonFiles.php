<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TrSalesNonFiles extends Model
{
    use HasFactory;

    protected $table = 'tr_sales_non_files';
    protected $guarded = ['id'];
}
