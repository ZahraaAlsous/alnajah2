<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;
use App\Models\Out_Of_Work_Employee;

class Employee extends Model
{
    use HasFactory,Notifiable,HasApiTokens;
    protected $fillable = [
        'first_name',
        'last_name',
        'phone',
        'address',
        'salary',
        'type',
        'year',
        'email',
        'password',
        'status',
    ];

    public function out_of_work_employee()
{
    return $this->hasMany('App\Models\Out_Of_Work_Employee',foreignKey:'employee_id');
}

public function maturitie()
    {
        return $this->hasMany('App\Models\Maturitie',foreignKey:'employee_id');
    }

    public function salary()
    {
        return $this->hasMany('App\Models\Salary',foreignKey:'employee_id',localKey:'id');
    }

}
