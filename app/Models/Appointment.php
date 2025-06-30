<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Appointment extends Model
{
    use HasFactory;

    protected $fillable = [
        'client_id',
        'dog_id',
        'appointment_date',
        'start_time',
        'end_time',
        'status',
        'total_price',
        'subscription_id',
        'notes'
    ];

    protected $casts = [
        'appointment_date' => 'date',
        'start_time' => 'datetime',
        'end_time' => 'datetime',
        'total_price' => 'decimal:2'
    ];

    /**
     * Get the client for this appointment.
     */
    public function client()
    {
        return $this->belongsTo(Client::class);
    }

    /**
     * Get the dog for this appointment.
     */
    public function dog()
    {
        return $this->belongsTo(Dog::class);
    }

    /**
     * Get the subscription for this appointment.
     */
    public function subscription()
    {
        return $this->belongsTo(Subscription::class);
    }

    /**
     * Get the services for this appointment.
     */
    public function services()
    {
        return $this->belongsToMany(Service::class, 'appointment_services')
                    ->withPivot('price')
                    ->withTimestamps();
    }
}
