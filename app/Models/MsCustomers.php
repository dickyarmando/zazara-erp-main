<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MsCustomers extends Model
{
    use HasFactory;

    protected $table = 'ms_customers';

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
        'npwp',
        'is_status',
    ];
}
