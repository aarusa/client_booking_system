<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Service extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'description',
        'duration',
        'is_active'
    ];

    protected $casts = [
        'duration' => 'integer',
        'is_active' => 'boolean'
    ];

    /**
     * Get the service prices for this service.
     */
    public function prices()
    {
        return $this->hasMany(ServicePrice::class);
    }

    /**
     * Get the price for a specific dog size.
     */
    public function getPriceForSize($dogSize)
    {
        return $this->prices()
                   ->where('dog_size', $dogSize)
                   ->value('price');
    }

    /**
     * Get all prices as an array.
     */
    public function getAllPrices()
    {
        return $this->prices()
                   ->pluck('price', 'dog_size')
                   ->toArray();
    }

    /**
     * Get the appointments for this service.
     */
    public function appointments()
    {
        return $this->belongsToMany(Appointment::class, 'appointment_services')
                    ->withPivot('price')
                    ->withTimestamps();
    }
}
