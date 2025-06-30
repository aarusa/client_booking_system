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
        'notes',
        'before_photo',
        'after_photo',
        'general_photos'
    ];

    protected $casts = [
        'general_photos' => 'array',
        'weight' => 'decimal:2',
        'age' => 'integer',
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

    /**
     * Get the before photo URL.
     */
    public function getBeforePhotoUrlAttribute()
    {
        return $this->before_photo ? asset('storage/' . $this->before_photo) : null;
    }

    /**
     * Get the after photo URL.
     */
    public function getAfterPhotoUrlAttribute()
    {
        return $this->after_photo ? asset('storage/' . $this->after_photo) : null;
    }

    /**
     * Get the general photos URLs.
     */
    public function getGeneralPhotoUrlsAttribute()
    {
        if (!$this->general_photos) {
            return [];
        }
        
        return collect($this->general_photos)->map(function($photo) {
            return asset('storage/' . $photo);
        })->toArray();
    }

    /**
     * Check if dog has before photo.
     */
    public function hasBeforePhoto()
    {
        return !empty($this->before_photo);
    }

    /**
     * Check if dog has after photo.
     */
    public function hasAfterPhoto()
    {
        return !empty($this->after_photo);
    }

    /**
     * Check if dog has general photos.
     */
    public function hasGeneralPhotos()
    {
        return !empty($this->general_photos) && count($this->general_photos) > 0;
    }

    /**
     * Get the number of general photos.
     */
    public function getGeneralPhotoCountAttribute()
    {
        return $this->general_photos ? count($this->general_photos) : 0;
    }
}
