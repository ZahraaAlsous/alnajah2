<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;
use App\Models\Student;

use App\Models\Note_Student;
use App\Models\Note;
use App\Models\Teacher;
use App\Models\Parentt;
use App\Models\Actions_log;


class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'first_name',
        'last_name',
        'father_name',
        'mother_name',
        'birthday',
        'gender',
        'phone',
        'address',
        'year',
        'image',
        'email',
        'password',
        'conf_password',
        'fcm_token',
        'user_type',
        'status',

    ];

    public function student()
    {
        return $this->hasOne('App\Models\Student',foreignKey:'user_id');
    }

    public function note_students()
    {
        return $this->hasMany('App\Models\Note_Student',foreignKey:'user_id',localKey:'id');
    }

    public function note()
    {
        return $this->hasMany('App\Models\Note',foreignKey:'user_id',localKey:'id');
    }

    // public function teacher(){
    //     return $this->hasMany('App\Models\Teacher',foreignKey:'user_id',localKey:'id');
    // }
    public function teacher()
    {
        return $this->hasOne('App\Models\Teacher',foreignKey:'user_id');
    }

    // public function actionLogs(): HasMany
    // {
    //     return $this->hasMany(Actions_log::class);
    // }

    
    public function actionLogs()
    {
        return $this->hasMany('App\Models\Actions_log',foreignKey:'user_id',localKey:'id');
    }









    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'conf_password',
        'remember_token',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
        'password' => 'hashed',
    ];
}
