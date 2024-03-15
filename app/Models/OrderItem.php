<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class OrderItem extends Model
{
    use HasFactory;
    protected  $table = 'order_items';

    protected $fillable = [
        'product_id',
        'quantity'
        ];

    protected function partner()
    {
        return $this->belongsTo(Partner::class, 'partner_id', 'id');
    }

    protected function order()
    {
        return $this->belongsTo(Order::class, 'order_id', 'id');
    }
    protected function product()
    {
        return $this->belongsTo(Product::class, 'product_id', 'id');
    }
    protected function buyer()
    {
        return $this->belongsTo(User::class, 'user_id', 'id');
    }
}
