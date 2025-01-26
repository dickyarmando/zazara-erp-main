<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TrInvoicesNon extends Model
{
    use HasFactory;

    protected $table = 'tr_invoices_nons';
    protected $guarded = ['id'];
}
