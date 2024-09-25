<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\Teacher;
use App\Models\Section;

class Teacher_Schedule extends Model
{
    use HasFactory;

    protected $fillable = [
        'teacher_id',
        'day_of_week',
        'start_time',
        'end_time',
        'section_id',
    ];

    public function teacher()
    {
        return $this->belongsTo(Teacher::class);
    }

    public function section()
    {
        return $this->belongsTo(Section::class);
    }
}
