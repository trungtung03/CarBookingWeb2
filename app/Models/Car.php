<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Car extends Model
{
    use HasFactory;

    protected $fillable = [
        'departure_location', 'destination', 'name', 'license_plates', 'image', 'price', 'type_name', 'id_user'
    ];

    public function type()
    {
        return $this->belongsTo(CarType::class, 'type_name', 'type_name');
    }

    public function user()
    {
        return $this->belongsTo(User::class, 'id_user');
    }

    public function seats()
    {
        return $this->belongsToMany(Seat::class, 'car_name')->withPivot('isBooked');
    }

    public function bills()
    {
        return $this->belongsToMany(Bill::class, 'car_bill');
    }

    public function schedules()
    {
        return $this->belongsToMany(Schedule::class, 'car_schedule');
    }
}
