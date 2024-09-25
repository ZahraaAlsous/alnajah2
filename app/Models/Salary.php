<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\Teacher;
class Salary extends Model
{
    use HasFactory;
    protected $table = 'salary';

    protected $fillable = [
        'salary_of_teacher',
        'month',
        'year',
        'teacher_id',
        'employee_id',
        'status',
        'num_houre',

    ];
    protected $timestamp = true;

    public function teacher()
    {
        return $this->belongsTo('App\Models\Teacher',foreignKey:'teacher_id');
    }

    public function employee()
    {
        return $this->belongsTo('App\Models\Employee',foreignKey:'employee_id');
    }
}
