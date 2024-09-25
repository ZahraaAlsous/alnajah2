<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

class Bus extends Model
{
    use HasFactory,Notifiable,HasApiTokens;
    protected $fillable = [
        'first_name',
        'last_name',
        'phone',
        'address',
        'year',
        'cost_from_bus',
    ];

}
