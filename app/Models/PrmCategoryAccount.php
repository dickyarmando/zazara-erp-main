<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PrmCategoryAccount extends Model
{
    use HasFactory;

    protected $table = 'prm_category_accounts';
    protected $guarded = ['id'];
}
