<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TrPurchaseFiles extends Model
{
    use HasFactory;

    protected $table = 'tr_purchase_files';
    protected $guarded = ['id'];
}
