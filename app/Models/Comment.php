<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;
use App\Models\Student;
use App\Models\Post;
use App\Models\Teacher;

class Comment extends Model
{
    use HasFactory,Notifiable,HasApiTokens;
    protected $fillable =[
        'description',
        'student_id',
        'post_id',
        'teacher_id',

    ];

    public function student(){
        return $this->belongsTo('App\Models\Student',foreignKey:'student_id');
    }

    public function post(){
        return $this->belongsTo('App\Models\Post',foreignKey:'post_id');
    }

    public function teacher(){
        return $this->belongsTo('App\Models\Teacher',foreignKey:'teacher_id');
    }



}
