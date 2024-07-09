<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Schedule extends Model
{
    use HasFactory;
    protected $fillable = ['date'];

    public function cars()
    {
        return $this->belongsToMany(Car::class, 'car_schedule');
    }

    public function times()
    {
        return $this->belongsToMany(Time::class, 'schedule_time');
    }
}
