<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TrPurchaseDetails extends Model
{
    use HasFactory;

    protected $table = 'tr_purchase_details';
    protected $guarded = ['id'];
}
