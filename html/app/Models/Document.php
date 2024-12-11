<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Document extends Model
{
    use HasFactory;

    protected $table = 'document';

    protected $fillable = [
        'candidate_id','ofc_joining_date','ssc','hsc','degree'.'offer_latter', 'sallery_slip' , 'reg_later ' , 'pan_card' , 'adhar_card'
    ];
}
