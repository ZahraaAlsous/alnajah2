<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;
use App\Models\Course;
use App\Models\Image;
class Publish extends Model
{
    use HasApiTokens, HasFactory, Notifiable;

    protected $fillable = [
        'description',
        'course_id',
    ];

    public function course(){
        return $this->belongsTo('App\Models\Course',foreignKey:'course_id');
    }

    public function image(){
        return $this->hasMany('App\Models\Image',foreignKey:'publish_id',localKey:'id');
    }
}
