<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Client extends Model
{
    use HasFactory;

    protected $fillable = [
        'first_name',
        'last_name',
        'email',
        'phone',
        'address',
        'city',
        'state',
        'zipcode',
        'notes',
        'reminder'
    ];

    /**
     * Get the client's full name.
     */
    public function getFullNameAttribute()
    {
        return $this->first_name . ' ' . $this->last_name;
    }

    /**
     * Get the client's full address.
     */
    public function getFullAddressAttribute()
    {
        $address = $this->address;
        if ($this->city) {
            $address .= ', ' . $this->city;
        }
        if ($this->state) {
            $address .= ', ' . $this->state;
        }
        if ($this->zipcode) {
            $address .= ' ' . $this->zipcode;
        }
        return $address;
    }

    /**
     * Get the dogs for this client.
     */
    public function dogs()
    {
        return $this->hasMany(Dog::class);
    }

    /**
     * Get the appointments for this client.
     */
    public function appointments()
    {
        return $this->hasMany(Appointment::class);
    }

    /**
     * Get the subscriptions for this client.
     */
    public function subscriptions()
    {
        return $this->hasMany(Subscription::class);
    }
}
