<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class NewVacancy extends Model
{
    use HasFactory;

    protected $table = 'vacancy';

    protected $fillable = [
        'comapny_id',
        'number_of_post',
        'vartical_id',
        'sub_location',
        'number_of_vacancy',
    ];
}
