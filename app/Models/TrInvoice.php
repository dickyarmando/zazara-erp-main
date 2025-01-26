<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TrInvoice extends Model
{
    use HasFactory;

    protected $table = 'tr_invoices';
    protected $guarded = ['id'];
}
