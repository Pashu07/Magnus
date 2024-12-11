<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TeamLead extends Model
{
    use HasFactory;

    protected $table = 'leads';

    protected $fillable = [
        'name',
        'email',
        'status',
        'schedule',
        'branch',
        'ofc_visit_date',
        'com_name',
        'designation',
        'status_remark',
        'int_com_name',
        'int_des_name',
        'int_des_date',
        'int_remark',
        'not_intrest',
        'not_answer',
        'call_back',
        'wrong_no'
    ];
}
