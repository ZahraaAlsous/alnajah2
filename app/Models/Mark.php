<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;
use App\Models\Student;
use App\Models\Subject;

class Mark extends Model
{
    use HasApiTokens, HasFactory, Notifiable;

    protected $fillable = [
        'ponus',
        'homework',
        'oral',
        'test1',
        'test2',
        'exam_med',
        'exam_final',
        'state',
        'student_id',
        'subject_id',
    ];

    public function student(){
        return $this->belongsTo('App\Models\Student',foreignKey:'student_id');
    }

    public function subject(){
        return $this->belongsTo('App\Models\Subject',foreignKey:'subject_id');
    }
}
