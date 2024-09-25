<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;
use App\Models\Homework;

class Accessories extends Model
{
    use HasApiTokens, HasFactory, Notifiable;

    protected $fillable = [
        'discription',
        'path',
        'home_work_id',
    ];

    public function homework(){
        return $this->belongsTo('App\Models\Homework',foreignKey:'home_work_id');
    }
}
