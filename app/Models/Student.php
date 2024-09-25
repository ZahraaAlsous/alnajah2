<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;
use App\Models\User;
use App\Models\Classs;
use App\Models\Section;
use App\Models\Parentt;
use App\Models\Pay_Fee;
use App\Models\Note_Student;
use App\Models\Comment;
use App\Models\Out_Of_Work_Student;
use App\Models\Image;
use App\Models\Mark;


class Student extends Model
{
    use HasApiTokens, HasFactory, Notifiable;

    protected $fillable = [
        'student_type',
        'calssification',
        'school_tuition',
        'user_id',
        'class_id',
        'section_id',
        'parentt_id',
    ];
    public function user(){
        return $this->belongsTo('App\Models\User',foreignKey:'user_id');
    }

    public function classs(){
        return $this->belongsTo('App\Models\Classs',foreignKey:'class_id');
    }
    public function section(){
        return $this->belongsTo('App\Models\Section',foreignKey:'section_id');
    }

    public function parentt(){
        return $this->belongsTo('App\Models\Parentt',foreignKey:'parentt_id');
    }

    public function pay_fees()
    {
        return $this->hasMany('App\Models\Pay_Fee',foreignKey:'student_id',localKey:'id');
    }

    public function note_students()
    {
        return $this->hasMany('App\Models\Note_Student',foreignKey:'student_id',localKey:'id');
    }

    public function comments()
    {
        return $this->hasMany('App\Models\Comment',foreignKey:'student_id',localKey:'id');
    }
    public function out_of_work_student(){
        return $this->hasMany('App\Models\Out_Of_Work_Student',foreignKey:'student_id',localKey:'id');
    }
    public function image(){
        return $this->hasMany('App\Models\Image',foreignKey:'program_student_id',localKey:'id');
    }

    public function mark(){
        return $this->hasMany('App\Models\Mark',foreignKey:'student_id',localKey:'id');
    }

    public function order(){
        return $this->hasMany('App\Models\Order',foreignKey:'student_id',localKey:'id');
    }



}
