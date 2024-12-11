<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Qualification extends Model
{
    use HasFactory;

    protected $table = 'qualification';

    protected $fillable = [
        'board_name',
        'passing_year',
        'institute',
        'candidate_id',
    ];

}
