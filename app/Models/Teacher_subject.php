<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

class Teacher_subject extends Model
{
    use HasApiTokens, HasFactory, Notifiable;

    protected $fillable = [
        'subject_id',
        'teacher_id',
    ];

    public function subject()
    {
        return $this->belongsTo('App\Models\Subject',foreignKey:'subject_id');
    }

    public function teacher()
    {
        return $this->belongsTo('App\Models\Teacher',foreignKey:'teacher_id');
    }
}
