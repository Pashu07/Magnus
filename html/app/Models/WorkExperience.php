<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class WorkExperience extends Model
{
    use HasFactory;
    protected $table = 'work_experience';

    protected $fillable = [
        'candidate_id',
        'company_name',
        'designation',
        'location',
        'ctc',
        'department',
        'start_date',
        'end_date'
    ];
}
