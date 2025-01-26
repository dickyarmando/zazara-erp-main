<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MsPaymentMethods extends Model
{
    use HasFactory;

    protected $table = 'ms_payment_methods';
    protected $guarded = ['id'];
}
