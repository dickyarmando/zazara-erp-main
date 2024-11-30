<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MsSuppliers extends Model
{
    use HasFactory;

    protected $table = 'ms_suppliers';

    protected $fillable = [
        'code',
        'name',
        'company_name',
        'address',
        'country_id',
        'state_id',
        'city_id',
        'postal_code',
        'email',
        'phone',
        'telephone',
        'fax',
        'is_status',
    ];
}
