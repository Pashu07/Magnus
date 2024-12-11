<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class RecuiterBranch extends Model
{
    use HasFactory;

    protected $table = 'recuiter_branch';

    protected $fillable =
    [
        'branch_name',
        'recuiters_id'
    ];
}
