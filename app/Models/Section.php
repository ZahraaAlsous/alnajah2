<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;
use App\Models\Student;
use App\Models\Post;
use App\Models\Program_Student;
use App\Models\Teacher_section;
use App\Models\Teacher;
use App\Models\Teacher_Schedule;

class Section extends Model
{
    use HasApiTokens, HasFactory, Notifiable;

    protected $fillable = [
        'num_section',
        'class_id',
    ];

    public function student()
    {
        return $this->hasMany('App\Models\Student',foreignKey:'section_id',localKey:'id');
    }

    public function posts()
    {
        return $this->hasMany('App\Models\Post',foreignKey:'section_id',localKey:'id');
    }

    public function program_student(){
        return $this->hasMany('App\Models\Program_Student',foreignKey:'section_id',localKey:'id');
    }

    public function classs()
    {
        return $this->belongsTo('App\Models\classs',foreignKey:'class_id');
    }

    // public function teacher_section(){
    //     return $this->hasMany('App\Models\Teacher_section',foreignKey:'section_id',localKey:'id');
    // }

    public function teachers()
{
    return $this->belongsToMany(Teacher::class, 'teacher_sections', 'section_id','teacher_id');
}

public function teacher_Schedules(){
    return $this->hasMany('App\Models\Teacher_Schedule',foreignKey:'section_id',localKey:'id');
}

}
