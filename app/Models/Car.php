<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Car extends Model
{
    use HasFactory;
    protected $table = 'cars';

    public function years() {
        return $this->belongsToMany(Year::class, 'car_year_relation', 'car_id', 'year_id');
    }
}
