<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Link extends Model
{
    use HasFactory;

	protected $fillable = [
        'account_id',
        'client_id',
        'employee_id',
        'created_by',
        'is_active'
    ];	

	protected $casts = [
        'created_at' => 'datetime',
    ];

}
