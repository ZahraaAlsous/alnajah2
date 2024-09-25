<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

class Academy extends Model
{
    use HasApiTokens, HasFactory, Notifiable;

    protected $fillable = [
        'name',
        'phone1',
        'phone2',
        'address',
        'facebook_link',
        'description',
        'year',
        'resolve_brother',
        'resolve_martyr',
        'resolve_Son_teacher',
    ];


}
