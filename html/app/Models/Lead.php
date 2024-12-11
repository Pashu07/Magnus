<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Lead extends Model
{
    use HasFactory;
    protected $table = 'leads';

    protected $fillable=[
        'round',
        'name',
        'email',
        'status',
        'schedule',
        'branch',
        'ofc_visit_date',
        'company_id',
        'location_id',
        'recruiter_branch',
        'recruiter_visit_date',
        'recruiter_name',
        'recruiter_designation',

        'city_name',
        'state_name',
        'designation',
        'status_remark',
        'int_com_name',
        'int_des_name',
        'int_des_date',
        'int_remark',
        'not_contact',
        'not_answer',
        'wrong_no',
        'call_back',
        'call_back_date',
        'unattended'

    ];
}
