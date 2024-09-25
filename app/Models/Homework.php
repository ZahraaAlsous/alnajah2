<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;
use App\Models\Classs;
use App\Models\Subject;
use App\Models\Accessories;

class Homework extends Model
{
    use HasFactory,Notifiable,HasApiTokens;
    protected $fillable = [
        'description',
        'year',
        'class_id',
        'subject_id',

    ];

    public function classs(){
        return $this->belongsTo('App\Models\Classs',foreignKey:'class_id');
    }

    public function subject(){
        return $this->belongsTo('App\Models\Subject',foreignKey:'subject_id');
    }

    public function accessories()
    {
        return $this->hasMany('App\Models\Accessories',foreignKey:'home_work_id',localKey:'id');
    }

}
