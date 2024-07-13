<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CarType extends Model
{
    use HasFactory;
    protected $primaryKey = 'type_name';
    public $incrementing = false;
    protected $keyType = 'string';

    protected $fillable = ['type_name', 'number'];

    public function cars()
    {
        return $this->hasMany(Car::class, 'type_name', 'type_name');
    }
}
