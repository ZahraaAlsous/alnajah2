<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;
use App\Models\Image_Archive;
use App\Models\File_Archive;
use App\Models\Classs;
use App\Models\Subject;

class Archive extends Model
{
    use HasApiTokens, HasFactory, Notifiable;

    protected $fillable = [
        'year',
        'class_id',
        'subject_id',
    ];


    public function image_Archives()
    {
        return $this->hasMany('App\Models\Image_Archive',foreignKey:'archive_id',localKey:'id');
    }

    public function file_Archive()
    {
        return $this->hasMany('App\Models\File_Archive',foreignKey:'archive_id',localKey:'id');
    }

    public function class(){
        return $this->belongsTo('App\Models\Classs',foreignKey:'class_id');
    }

    public function subject(){
        return $this->belongsTo('App\Models\Subject',foreignKey:'subject_id');
    }

}
