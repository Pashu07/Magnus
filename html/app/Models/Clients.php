<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Clients extends Model
{
    use HasFactory;
    protected $table = 'clients';

    protected $fillable = [
        'id',
        'name',
        'email',
        'mobile',
        'check_in_time',
        'check_out_time',
        'number_of_people',
        'number_of_children',
        'number_of_pets',
        'breakfast',
        'lunch',
        'hightea',
        'dinner',
        'total_prize'
    ];



    protected $guarded = [];


    protected $hidden = [
        'remember_token',
        '_token',
    ];


    protected $casts = [
        'created_at' => 'datetime',
    ];
}
