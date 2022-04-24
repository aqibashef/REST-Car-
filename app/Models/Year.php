<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Year extends Model
{
    use HasFactory;

    protected $table = 'years';

    protected $fillable = ['year'];

    public function cars() {
        return $this->belogngsToMany(Car::class, 'car_year_relation', 'year_id', 'car_id');
    }
}
