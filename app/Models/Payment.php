<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Payment extends Model
{
    protected $fillable = [
        'order_id',
        'payment_method',
        'payment_channel',
        'transaction_code',
        'amount',
        'va_number',
        'status',
        'paid_at',
        'notes',
    ];

    protected $casts = [
        'paid_at' => 'datetime',
    ];

    public function order()
    {
        return $this->belongsTo(Order::class);
    }

    public function getMethodNameAttribute(): string
    {
        return match ($this->payment_method) {
            'qris' => 'QRIS (Gopay/OVO/ShopeePay/BCA)',
            'bank_transfer' => 'Virtual Account Bank',
            'ewallet' => 'E-Wallet',
            default => strtoupper($this->payment_method),
        };
    }
}
