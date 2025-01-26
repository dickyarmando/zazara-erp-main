<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TrPurchaseNonFiles extends Model
{
    use HasFactory;

    protected $table = 'tr_purchase_non_files';
    protected $guarded = ['id'];
}
