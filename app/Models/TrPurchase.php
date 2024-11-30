<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TrPurchase extends Model
{
    use HasFactory;

    protected $table = 'tr_purchase';
    protected $guarded = ['id'];
}
