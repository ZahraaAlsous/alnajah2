<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;
use App\Models\Student;
use App\Models\User;

class Note_Student extends Model
{
    use HasApiTokens, HasFactory, Notifiable;

    protected $fillable = [
        'type',
        'text',
        'student_id',
        'user_id',
    ];

    public function student(){
        return $this->belongsTo('App\Models\Student',foreignKey:'student_id');
    }

    public function user(){
        return $this->belongsTo('App\Models\User',foreignKey:'user_id');
    }
}
