<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PincodeLocation extends Model
{
    use HasFactory;

    protected $table = 'vacancy_location';

    protected $fillable = [
        'pincode_id',
        'location_name',
        'city_name',
        'state_name',
        'number_of_vacancy',
    ];
}
