<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;
use App\Models\Section;
use App\Models\Subject;
use App\Models\Teacher;
use App\Models\Comment;

class Post extends Model
{
    use HasApiTokens, HasFactory, Notifiable;

    protected $fillable = [
        'quostion',
        'state_on_off',
        'section_id',
        'subject_id',
        'teacher_id',
    ];

    public function section(){
        return $this->belongsTo('App\Models\Section',foreignKey:'section_id');
    }

    public function subject(){
        return $this->belongsTo('App\Models\Subject',foreignKey:'subject_id');
    }

    public function teacher(){
        return $this->belongsTo('App\Models\Teacher',foreignKey:'teacher_id');
    }

    public function comments()
    {
        return $this->hasMany('App\Models\Comment',foreignKey:'post_id',localKey:'id');
    }
}
