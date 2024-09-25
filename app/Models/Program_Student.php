<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;
use App\Models\Section;
use App\Models\Image;

class Program_Student extends Model
{
    use HasFactory,Notifiable,HasApiTokens;
    protected $fillable = [
        'type',
        'section_id'
    ];

    public function section(){
        return $this->belongsTo('App\Models\Section',foreignKey:'section_id');
    }

    public function image(){
        return $this->hasMany('App\Models\Image',foreignKey:'program_student_id',localKey:'id');
    }


}
