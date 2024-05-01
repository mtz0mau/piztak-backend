<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class OrderProduct extends Model
{
    use HasFactory;
    protected $table = 'order_product';
    protected $primaryKey = 'id';

    protected $fillable = [
        'quantity',
        'unit_price',
        'name',
        'extra_ingredients',
        'extra_price',
        'order_id',
        'product_id',
        'size_id',
        'status'
    ];

    protected $appends = ['total_price'];

    public function products()
    {
        return $this->belongsTo(Product::class);
    }

    public function getTotalPriceAttribute()
    {
        return ($this->unit_price + $this->extra_price) * $this->quantity;
    }   
}
