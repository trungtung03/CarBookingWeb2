<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Time extends Model
{
    use HasFactory;
    protected $fillable = ['depature', 'arrival'];

    public function schedules()
    {
        return $this->belongsToMany(Schedule::class, 'schedule_time');
    }
}
