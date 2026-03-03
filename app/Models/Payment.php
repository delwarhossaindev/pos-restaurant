<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Payment extends Model
{
    use HasFactory;

    protected $fillable = [
        'order_id', 'user_id', 'amount', 'paid_amount',
        'change_amount', 'method', 'transaction_id', 'status'
    ];

    protected $casts = [
        'amount' => 'decimal:2',
        'paid_amount' => 'decimal:2',
        'change_amount' => 'decimal:2',
    ];

    public function order()
    {
        return $this->belongsTo(Order::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function getMethodLabelAttribute()
    {
        return match($this->method) {
            'cash' => 'নগদ',
            'card' => 'কার্ড',
            'mobile_banking' => 'মোবাইল ব্যাংকিং',
            'bkash' => 'বিকাশ',
            'nagad' => 'নগদ (অ্যাপ)',
            default => $this->method,
        };
    }
}
