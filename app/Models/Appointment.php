<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;
use App\Models\Order;
class Appointment extends Model
{
    use HasFactory,Notifiable,HasApiTokens;
    protected $fillable =[
        'date',
        'order_id',
    ];
    public function order()
    {
        return $this->belongsTo('App\Models\Order',foreignKey:'order_id');
    }
}
