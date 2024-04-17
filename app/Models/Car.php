<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Car extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'brand',
        'model',
        'police_number',
        'rent_fee',
        'image',
    ];

    protected $appends = [
        'is_rent',
        'rented',
    ];

    public function user(){
        return $this->belongsTo(User::class);
    }

    public function rentCar()
    {
        return $this->hasMany(RentCar::class);
    }

    public function rentedBy()
    {
        return $this->hasOne(RentCar::class)->latest();
    }

    public function getIsRentAttribute()
    {
        return $this->rentCar()->whereDate('start_date', '<=', now())->whereDate('end_date', '>=', now())->count() > 0;
    }
    public function getRentedAttribute()
    {
        return $this->rentCar->count();
    }
}
