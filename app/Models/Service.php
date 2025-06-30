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
        'price',
        'duration',
        'is_active'
    ];

    protected $casts = [
        'price' => 'decimal:2',
        'duration' => 'integer',
        'is_active' => 'boolean'
    ];

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
