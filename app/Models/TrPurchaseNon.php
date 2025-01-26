<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TrPurchaseNon extends Model
{
    use HasFactory;

    protected $table = 'tr_purchase_non';
    protected $guarded = ['id'];
}
