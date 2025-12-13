<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Payment extends Model
{
    use HasFactory;

    protected $fillable = [
        'order_id',
        'user_id',
        'payment_code',
        'amount',
        'method',
        'status',
        'proof_image',
        'notes',
        'verified_at',
        'verified_by'
    ];

    protected $casts = [
        'amount' => 'decimal:2',
        'verified_at' => 'datetime',
    ];

    protected static function boot()
    {
        parent::boot();

        static::creating(function ($payment) {
            if (!$payment->payment_code) {
                $payment->payment_code = 'PAY-' . date('Ymd') . '-' . strtoupper(substr(uniqid(), -6));
            }
        });
    }

    // Relationships
    public function order()
    {
        return $this->belongsTo(Order::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function verifiedBy()
    {
        return $this->belongsTo(User::class, 'verified_by');
    }

    // Scopes
    public function scopePending($query)
    {
        return $query->where('status', 'pending');
    }

    public function scopeProcessing($query)
    {
        return $query->where('status', 'processing');
    }

    public function scopeSuccess($query)
    {
        return $query->where('status', 'success');
    }

    public function scopeFailed($query)
    {
        return $query->where('status', 'failed');
    }

    // Helper Methods
    public function isPending()
    {
        return $this->status === 'pending';
    }

    public function isProcessing()
    {
        return $this->status === 'processing';
    }

    public function isSuccess()
    {
        return $this->status === 'success';
    }

    public function isFailed()
    {
        return $this->status === 'failed';
    }

    public function canBeVerified()
    {
        return in_array($this->status, ['pending', 'processing']);
    }

    public function getStatusBadgeAttribute()
    {
        $badges = [
            'pending' => 'warning',
            'processing' => 'info',
            'success' => 'success',
            'failed' => 'danger'
        ];

        return $badges[$this->status] ?? 'secondary';
    }

    public function getMethodBadgeAttribute()
    {
        $badges = [
            'cash' => 'success',
            'transfer_bca' => 'primary',
            'transfer_mandiri' => 'primary',
            'transfer_bni' => 'primary',
            'gopay' => 'success',
            'ovo' => 'warning',
            'dana' => 'info',
            'shopeepay' => 'danger',
            'credit_card' => 'secondary'
        ];

        return $badges[$this->method] ?? 'secondary';
    }

    public function getMethodNameAttribute()
    {
        $methods = [
            'cash' => 'Tunai',
            'transfer_bca' => 'Transfer BCA',
            'transfer_mandiri' => 'Transfer Mandiri',
            'transfer_bni' => 'Transfer BNI',
            'gopay' => 'GoPay',
            'ovo' => 'OVO',
            'dana' => 'DANA',
            'shopeepay' => 'ShopeePay',
            'credit_card' => 'Kartu Kredit'
        ];

        return $methods[$this->method] ?? $this->method;
    }
}
