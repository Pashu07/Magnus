<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Company extends Model
{
    use HasFactory;

    protected $table = 'companies';

    protected $fillable = [
        'company_logo',
        'company_name',
        'designation',
        'vartical_name',
        'pincode',
        'location_name',
        'city_name',
        'state_name',
        'sub_location_name',
        'post'
    ];

}
