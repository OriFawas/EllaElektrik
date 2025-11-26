<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Order extends Model
{
    protected $fillable = [
        'user_id',
        'status',
        'subtotal',
        'service_fee',
        'total',
        'pickup_deadline',
        'completed_at',
    ];

    protected $casts = [
        'subtotal' => 'decimal:2',
        'service_fee' => 'decimal:2',
        'total' => 'decimal:2',
        'pickup_deadline' => 'datetime',
        'completed_at' => 'datetime',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function items(): HasMany
    {
        return $this->hasMany(OrderItem::class);
    }

    public function getDaysLeftAttribute(): int
    {
        if ($this->status !== 'ready_for_pickup' || !$this->pickup_deadline) {
            return 0;
        }

        $diff = Carbon::now()->diffInDays($this->pickup_deadline, false);
        return max(0, (int) ceil($diff));
    }

    public function isActive(): bool
    {
        return $this->status === 'ready_for_pickup';
    }

    public function isCompleted(): bool
    {
        return $this->status === 'completed';
    }

    public function scopeActive($query)
    {
        return $query->where('status', 'ready_for_pickup');
    }

    public function scopeCompleted($query)
    {
        return $query->whereIn('status', ['completed', 'cancelled']);
    }
}
