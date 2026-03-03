<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class RestaurantTable extends Model
{
    use HasFactory;

    protected $fillable = ['table_number', 'capacity', 'status', 'location'];

    public function orders()
    {
        return $this->hasMany(Order::class);
    }

    public function activeOrder()
    {
        return $this->hasOne(Order::class)->whereNotIn('status', ['completed', 'cancelled']);
    }

    public function isAvailable()
    {
        return $this->status === 'available';
    }

    public function getStatusColorAttribute()
    {
        return match($this->status) {
            'available' => 'success',
            'occupied' => 'danger',
            'reserved' => 'warning',
            'cleaning' => 'info',
            default => 'secondary',
        };
    }

    public function getStatusLabelAttribute()
    {
        return match($this->status) {
            'available' => 'খালি',
            'occupied' => 'অর্ডার চলছে',
            'reserved' => 'রিজার্ভ',
            'cleaning' => 'পরিষ্কার হচ্ছে',
            default => $this->status,
        };
    }
}
