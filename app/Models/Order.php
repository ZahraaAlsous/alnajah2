<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;
use App\Models\Appointment;
use App\Models\Student;
use App\Models\Course;

class Order extends Model
{
    use HasFactory,Notifiable,HasApiTokens;
    protected $fillable = [
        'first_name',
        'last_name',
        'father_name',
        'birthday',
        'gender',
        'phone',
        'address',
        'email',
        'classification',
        'class',
        'year',
        'student_id',
        'course_id',
        

    ];

    public function appointment()
    {
        return $this->hasMany('App\Models\Appointment',foreignKey:'order_id',localKey:'id');
    }

    public function student(){
        return $this->belongsTo('App\Models\Student',foreignKey:'student_id');
    }

    public function course(){
        return $this->belongsTo('App\Models\Course',foreignKey:'course_id');
    }
}
