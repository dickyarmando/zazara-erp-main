<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TrGeneralLedgerDetails extends Model
{
    use HasFactory;

    protected $table = 'tr_general_ledger_details';
    protected $guarded = ['id'];
}
