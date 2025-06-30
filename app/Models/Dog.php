<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Dog extends Model
{
    use HasFactory;

    protected $fillable = [
        'client_id',
        'name',
        'breed',
        'age',
        'gender',
        'photo',
        'weight',
        'coat_type',
        'spayed_neutered',
        'behavior',
        'tags',
        'notes'
    ];

    /**
     * Get the client that owns the dog.
     */
    public function client()
    {
        return $this->belongsTo(Client::class);
    }

    /**
     * Get the appointments for this dog.
     */
    public function appointments()
    {
        return $this->hasMany(Appointment::class);
    }
}
