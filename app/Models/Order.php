<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Order extends Model
{
    use HasFactory;

    protected $fillable = [
        'order_number', 'restaurant_table_id', 'user_id', 'order_type',
        'status', 'guests', 'subtotal', 'tax_amount', 'discount_amount',
        'total_amount', 'notes', 'customer_name', 'customer_phone'
    ];

    protected $casts = [
        'subtotal' => 'decimal:2',
        'tax_amount' => 'decimal:2',
        'discount_amount' => 'decimal:2',
        'total_amount' => 'decimal:2',
    ];

    public function table()
    {
        return $this->belongsTo(RestaurantTable::class, 'restaurant_table_id');
    }

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

    public function getStatusColorAttribute()
    {
        return match($this->status) {
            'pending' => 'warning',
            'confirmed' => 'info',
            'preparing' => 'primary',
            'ready' => 'success',
            'served' => 'success',
            'completed' => 'secondary',
            'cancelled' => 'danger',
            default => 'secondary',
        };
    }

    public function getStatusLabelAttribute()
    {
        return match($this->status) {
            'pending' => 'অপেক্ষায়',
            'confirmed' => 'নিশ্চিত',
            'preparing' => 'তৈরি হচ্ছে',
            'ready' => 'প্রস্তুত',
            'served' => 'পরিবেশিত',
            'completed' => 'সম্পন্ন',
            'cancelled' => 'বাতিল',
            default => $this->status,
        };
    }

    public static function generateOrderNumber()
    {
        $prefix = 'ORD';
        $date = now()->format('Ymd');
        $last = static::whereDate('created_at', today())->count() + 1;
        return $prefix . '-' . $date . '-' . str_pad($last, 4, '0', STR_PAD_LEFT);
    }

    public function recalculateTotals()
    {
        $settings = Setting::getValues();
        $taxRate = (float)($settings['tax_rate'] ?? 5);

        $this->subtotal = $this->items->sum('total_price');
        $this->tax_amount = round($this->subtotal * $taxRate / 100, 2);
        $this->total_amount = $this->subtotal + $this->tax_amount - $this->discount_amount;
        $this->save();
    }
}
