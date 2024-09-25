<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

use App\Models\Employee;
use App\Models\Teacher;

class Out_Of_Work_Employee extends Model
{
    use HasApiTokens, HasFactory, Notifiable;
    protected $table = 'out_of_work_employees' ;
    protected $fillable = [
        'date',
        'num_hour_out',
        'note',
        'employee_id',
        'teacher_id',
    ];

    public static function totalHoursOutOfWork($teacherId, $month)
    {
        return self::where('teacher_id', $teacherId)
                    ->whereMonth('date', $month)
                    ->sum('num_hour_out');
    }


    public function employee(){

        return $this->belongsTo('App\Models\Employee',foreignKey:'employee_id');

    }

    public function teacher(){
        return $this->belongsTo('App\Models\Teacher',foreignKey:'teacher_id');
    }
}
