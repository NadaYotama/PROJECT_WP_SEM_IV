<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Order extends Model
{
    use HasFactory;

    protected $fillable = [
        'order_number',
        'user_id',
        'order_type',
        'status',
        'subtotal',
        'tax',
        'delivery_fee',
        'total',
        'delivery_address',
        'delivery_phone',
        'notes',
        'payment_method',
        'payment_status',
        'payment_proof',
        'paid_at',
        'completed_at'
    ];

    protected $casts = [
        'paid_at' => 'datetime',
        'completed_at' => 'datetime',
        'subtotal' => 'decimal:2',
        'tax' => 'decimal:2',
        'delivery_fee' => 'decimal:2',
        'total' => 'decimal:2',
    ];

    protected static function boot()
    {
        parent::boot();
        
        static::creating(function ($order) {
            if (!$order->order_number) {
                $order->order_number = 'ORD-' . date('Ymd') . '-' . strtoupper(substr(uniqid(), -6));
            }
        });
    }

    // Relationships
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function items()
    {
        return $this->hasMany(OrderItem::class);
    }

    public function payment()
    {
        return $this->hasOne(Payment::class);
    }

    // Scopes
    public function scopePending($query)
    {
        return $query->where('status', 'pending');
    }

    public function scopeCompleted($query)
    {
        return $query->where('status', 'completed');
    }

    public function scopeDelivery($query)
    {
        return $query->where('order_type', 'delivery');
    }

    public function scopeDineIn($query)
    {
        return $query->where('order_type', 'dine_in');
    }

    // Helper Methods
    public function isPaid()
    {
        return $this->payment_status === 'paid';
    }

    public function canBeCancelled()
    {
        return in_array($this->status, ['pending', 'confirmed']);
    }

    public function getStatusBadgeAttribute()
    {
        $badges = [
            'pending' => 'warning',
            'confirmed' => 'info',
            'preparing' => 'primary',
            'ready' => 'success',
            'delivering' => 'info',
            'completed' => 'success',
            'cancelled' => 'danger'
        ];

        return $badges[$this->status] ?? 'secondary';
    }

    public function getPaymentStatusBadgeAttribute()
    {
        $badges = [
            'unpaid' => 'danger',
            'paid' => 'success',
            'refunded' => 'warning'
        ];

        return $badges[$this->payment_status] ?? 'secondary';
    }
}