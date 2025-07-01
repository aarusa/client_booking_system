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
        'notes',
        'payment_status',
        'payment_mode',
        'amount_paid',
        'paid_at',
        'services_data'
    ];

    protected $casts = [
        'appointment_date' => 'date',
        'start_time' => 'datetime',
        'end_time' => 'datetime',
        'total_price' => 'decimal:2',
        'amount_paid' => 'decimal:2',
        'paid_at' => 'datetime',
        'services_data' => 'array'
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

    /**
     * Get the outstanding balance for this appointment.
     */
    public function getOutstandingBalanceAttribute()
    {
        return $this->total_price - $this->amount_paid;
    }

    /**
     * Check if appointment is fully paid.
     */
    public function isFullyPaid()
    {
        return $this->amount_paid >= $this->total_price;
    }

    /**
     * Check if appointment is partially paid.
     */
    public function isPartiallyPaid()
    {
        return $this->amount_paid > 0 && $this->amount_paid < $this->total_price;
    }

    /**
     * Check if appointment is unpaid.
     */
    public function isUnpaid()
    {
        return $this->amount_paid == 0;
    }

    /**
     * Get payment status badge class.
     */
    public function getPaymentStatusBadgeClassAttribute()
    {
        return match($this->payment_status) {
            'paid' => 'bg-success',
            'pending' => 'bg-warning',
            'partial' => 'bg-info',
            'refunded' => 'bg-secondary',
            default => 'bg-secondary'
        };
    }

    /**
     * Get payment mode badge class.
     */
    public function getPaymentModeBadgeClassAttribute()
    {
        return match($this->payment_mode) {
            'cash' => 'bg-info',
            'payid' => 'bg-primary',
            'card' => 'bg-success',
            'bank_transfer' => 'bg-secondary',
            default => 'bg-secondary'
        };
    }

    /**
     * Mark appointment as paid.
     */
    public function markAsPaid($amount = null, $paymentMode = null)
    {
        $this->update([
            'payment_status' => 'paid',
            'amount_paid' => $amount ?? $this->total_price,
            'payment_mode' => $paymentMode,
            'paid_at' => now(),
        ]);
    }

    /**
     * Mark appointment as partially paid.
     */
    public function markAsPartiallyPaid($amount, $paymentMode = null)
    {
        $this->update([
            'payment_status' => 'partial',
            'amount_paid' => $amount,
            'payment_mode' => $paymentMode,
            'paid_at' => now(),
        ]);
    }
}
