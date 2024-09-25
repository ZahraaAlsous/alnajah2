<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;
use App\Models\Classs;

class Fee_School extends Model
{
    use HasFactory,Notifiable,HasApiTokens;
    protected $fillable = [
        'year',
        'amount',
        'class_id',
    ];

    public function classs(){
        return $this->belongsTo('App\Models\Classs',foreignKey:'class_id');
    }

}
