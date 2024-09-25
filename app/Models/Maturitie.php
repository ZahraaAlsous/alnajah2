<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Maturitie extends Model
{
    use HasFactory;


protected $table = "create__maturitie_tables";
protected $fillable = [
    'amount',
    'teacher_id',
    'employee_id'

];

protected $timestamp = true;


public function teacher()
{
    return $this->belongsTo('App\Models\Teacher',foreignKey:'teacher_id');
}

public function employee()
{
    return $this->belongsTo('App\Models\employee',foreignKey:'employee_id');
}

}
