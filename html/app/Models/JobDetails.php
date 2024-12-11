<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class JobDetails extends Model
{
    use HasFactory;

    protected $table = 'job_details';

    protected $fillable = [
        'job_title',
        'experience',
        'salery_up_to',
        'job_location',
        'job_type',
        'role_responsibilies',
        'candidate_profile'
    ];

}
