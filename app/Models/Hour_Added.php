<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\Teacher;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

class Hour_Added extends Model
{
    use HasApiTokens, HasFactory, Notifiable;


    protected $fillable =[
        'teacher_id',
        'num_hour_added',
        'note_hour_added',
    ];


    public function teacher(){
        return $this->belongsTo('App\Models\Teacher',foreignKey:'teacher_id');
    }


    public static function getTeacherHoursForMonth($teacherId, $month)
    {
        return self::where('teacher_id', $teacherId)
                    ->whereMonth('created_at', $month)
                    ->sum('num_hour_added');
    }

    public static function getTeacherHoursForDay($teacherId, $day)
    {
        return self::where('teacher_id', $teacherId)
                    ->whereDay('created_at', $day)
                    ->sum('num_hour_added');
    }

}
