<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Guests extends Model
{
    use HasFactory;
    protected $table = 'guests';
    protected $fillable = [
        'guest_name',
        'guest_number',
        'guest_idcard',
        'clients_id',
    ];
}
