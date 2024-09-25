<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\Student;
use App\Models\User;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

class Parentt extends Model
{
    use HasFactory ,Notifiable,HasApiTokens;

    protected $fillable = [
        'first_name',
        'last_name',
        'phone',
        'address',
        'image',
        'email',
        'password',
        'conf_password',
        'fcm_token',
        'status',
    ];

    public function student()
    {
        return $this->hasMany('App\Models\Student',foreignKey:'parentt_id',localKey:'id');
    }


}
