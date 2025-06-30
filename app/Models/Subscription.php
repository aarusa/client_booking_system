<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Subscription extends Model
{
    use HasFactory;

    protected $fillable = [
        'client_id',
        'dog_id',
        'subscription_name',
        'frequency',
        'frequency_weeks',
        'start_date',
        'end_date',
        'preferred_time',
        'price_per_session',
        'auto_book',
        'is_active',
        'notes'
    ];

    protected $casts = [
        'start_date' => 'date',
        'end_date' => 'date',
        'preferred_time' => 'datetime',
        'price_per_session' => 'decimal:2',
        'auto_book' => 'boolean',
        'is_active' => 'boolean'
    ];

    /**
     * Get the client for this subscription.
     */
    public function client()
    {
        return $this->belongsTo(Client::class);
    }

    /**
     * Get the dog for this subscription.
     */
    public function dog()
    {
        return $this->belongsTo(Dog::class);
    }

    /**
     * Get the appointments for this subscription.
     */
    public function appointments()
    {
        return $this->hasMany(Appointment::class);
    }
}
