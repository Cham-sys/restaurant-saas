<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Order_item extends Model
{
    use HasFactory;

    protected $fillable = [
        'order_id',
        'product_id',
        'product_name',
        'quantity',
        'price',
        'total',
    ];

    public function order()
    {
        return $this->belongsTo(Order::class);
    }

    public function product()
    {
        return $this->belongsTo(Product::class)->withTrashed(); // جلب المنتج حتى لو كان محذوفاً
    }
    protected $appends = ['name', 'qty'];

    public function getNameAttribute()
    {
        return $this->attributes['product_name'] ?? '';
    }

    public function getQtyAttribute()
    {
        return $this->attributes['quantity'] ?? 1;
    }
}
