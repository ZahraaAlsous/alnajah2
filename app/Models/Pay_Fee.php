<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;
use App\Models\Student;
use App\Models\Course;

class Pay_Fee extends Model
{
    use HasApiTokens, HasFactory, Notifiable;

    protected $fillable = [
        'type',
        'date',
        'amount_money',
        'remaining_fee',
        'student_id',
        'course_id',
    ];
 
    public function student(){
        return $this->belongsTo('App\Models\Student',foreignKey:'student_id');
    }

    public function course(){
        return $this->belongsTo('App\Models\Course',foreignKey:'course_id');
    }
}
