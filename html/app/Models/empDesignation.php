<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class empDesignation extends Model
{
    use HasFactory;
    protected $table = 'emp_designation';

    protected $fillable = [
        'name'
    ];

}
