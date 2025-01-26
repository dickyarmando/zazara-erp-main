<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TrGeneralLedger extends Model
{
    use HasFactory;

    protected $table = 'tr_general_ledgers';
    protected $guarded = ['id'];
}
