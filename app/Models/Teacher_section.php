<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;
use App\Models\Section;
use App\Models\Teacher;

class Teacher_section extends Model
{
    use HasApiTokens, HasFactory, Notifiable;

    protected $fillable = [
        'section_id',
        'teacher_id',
    ];

    // public function section()
    // {
    //     return $this->belongsTo('App\Models\Section',foreignKey:'section_id');
    // }

    // public function teacher()
    // {
    //     return $this->belongsTo('App\Models\Teacher',foreignKey:'teacher_id');
    // }
}
