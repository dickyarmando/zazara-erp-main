<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TrPurchaseNonDetails extends Model
{
    use HasFactory;

    protected $table = 'tr_purchase_non_details';
    protected $guarded = ['id'];
}
