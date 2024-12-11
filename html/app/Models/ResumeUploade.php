<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ResumeUploade extends Model
{
    use HasFactory;
    protected $table = 'resumeuploades';

    protected $fillable=[
        'resume'
    ];
}
