<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    use HasFactory, Notifiable;
    protected $fillable = [
        'full_name', 'password', 'email', 'phone_number', 'avatar', 'role', 'address', 'date_of_birth'
    ];

    protected $hidden = [
        'password', 'remember_token',
    ];

    public function cars()
    {
        return $this->hasMany(Car::class, 'id_user');
    }

    public function bills()
    {
        return $this->hasMany(Bill::class, 'id_user');
    }
}
