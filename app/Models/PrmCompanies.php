<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PrmCompanies extends Model
{
    use HasFactory;

    protected $table = 'prm_companies';
    protected $fillable = ['name', 'address', 'phone', 'telephone', 'fax', 'email', 'website', 'picture', 'is_status'];
}
