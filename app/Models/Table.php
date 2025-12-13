<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Table extends Model
{
    use HasFactory;

    protected $fillable = [
        'table_number',
        'capacity',
        'location',
        'status',
        'description'
    ];

    protected $casts = [
        'capacity' => 'integer',
    ];

    // Relationships
    public function reservations()
    {
        return $this->hasMany(Reservation::class);
    }

    // Scopes
    public function scopeAvailable($query)
    {
        return $query->where('status', 'available');
    }

    public function scopeOccupied($query)
    {
        return $query->where('status', 'occupied');
    }

    public function scopeReserved($query)
    {
        return $query->where('status', 'reserved');
    }

    // Helper Methods
    public function isAvailable()
    {
        return $this->status === 'available';
    }

    public function isOccupied()
    {
        return $this->status === 'occupied';
    }

    public function isReserved()
    {
        return $this->status === 'reserved';
    }

    public function getStatusBadgeAttribute()
    {
        $badges = [
            'available' => 'success',
            'occupied' => 'danger',
            'reserved' => 'warning',
            'maintenance' => 'secondary'
        ];

        return $badges[$this->status] ?? 'secondary';
    }

    public function getLocationBadgeAttribute()
    {
        $badges = [
            'indoor' => 'primary',
            'outdoor' => 'success',
            'vip' => 'warning'
        ];

        return $badges[$this->location] ?? 'secondary';
    }
}
