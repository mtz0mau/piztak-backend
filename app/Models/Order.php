<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Order extends Model
{
    use HasFactory;
    protected $table = 'orders';
    protected $primaryKey = 'id';

    protected $fillable = [
        'delivery_time',
        'delivery_price',
        'order_status',
        'coments',
        'delivery_option_id',
        'customer_id',
        'address_id',
        'coupon_id',
        'status',
        'front_id',
        'order_number'
    ];

    protected $appends = ['total_payments', 'subtotal', 'total', 'paid'];

    public function deliveryOption()
    {
        return $this->belongsTo(DeliveryOption::class);
    }

    public function customer()
    {
        return $this->belongsTo(Customer::class);
    }

    public function address()
    {
        return $this->belongsTo(Address::class);
    }

    public function coupon()
    {
        return $this->belongsTo(Coupon::class);
    }

    public function orderPayments()
    {
        return $this->hasMany(OrderPayment::class, 'order_id');
    }

    public function products()
    {
        return $this->belongsToMany(Product::class, 'order_product')->withPivot(['name', 'unit_price', 'quantity', 'size_id', 'extra_price', 'extra_ingredients']);
    }

    public function orderProducts()
    {
        return $this->hasMany(OrderProduct::class, 'order_id');
    }

    public function getTotalPaymentsAttribute()
    {
        return $this->orderPayments->where('payment_status', true)->sum('amount');
    }

    public function getPaidAttribute()
    {
        return $this->orderPayments->sum('amount');
    }

    public function getSubtotalAttribute()
    {
        $orderProducts = OrderProduct::where('order_id', $this->id)->get();
        return $orderProducts->sum('total_price');
    }

    public function getTotalAttribute()
    {
        // Incluir envío y hacer descuento en caso de ocupar cupón
        return $this->subtotal + $this->delivery_price;
    }

    public $timestamps = true;
    protected $date = ['deleted_at'];
}