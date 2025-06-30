<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ServicePrice extends Model
{
    use HasFactory;

    protected $fillable = [
        'service_id',
        'dog_size',
        'price'
    ];

    protected $casts = [
        'price' => 'decimal:2'
    ];

    /**
     * Get the service that owns this price.
     */
    public function service()
    {
        return $this->belongsTo(Service::class);
    }

    /**
     * Get the price for a specific dog size.
     */
    public static function getPriceForSize($serviceId, $dogSize)
    {
        return self::where('service_id', $serviceId)
                   ->where('dog_size', $dogSize)
                   ->value('price');
    }
}
