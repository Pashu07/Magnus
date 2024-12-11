<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class VacancyLocation extends Model
{
    use HasFactory;

    protected $table = 'vacancy_location';

    protected $fillable = [
        'location_id',
        'sub_location_id',
        'number_of_vacancy'
    ];
}
