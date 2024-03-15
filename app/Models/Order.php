<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class Order extends Model
{
    use HasFactory;

    protected $fillable = [
      'order_id',
      'item_id',
      'delivery_address',
      'order_status',
      'tracking_number',
      'notes',
      'payment_method'
    ];

    protected function orderItems()
    {
        return $this->hasMany(OrderItem::class, 'order_item', 'id');
    }

    protected function buyer(){
        return $this->belongsTo(User::class, 'user_id', 'id');
    }

    protected static function boot()
    {
        parent::boot();

        // Generate order number before saving the model
        static::creating(function ($order) {
            $order->order_id = Str::uuid()->toString();
            $order->tracking_number = 'TN' . time() . Str::random(4);
        });
    }

}
