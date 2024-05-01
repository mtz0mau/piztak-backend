<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class OrderPayment extends Model
{
    use HasFactory;
    protected $table = 'order_payments';
    protected $id = 'id';

    protected $fillable = [
        'amount',
        'payment_status',
        'status',
        'order_id',
        'payment_type_id'
    ];

    public function order()
    {
        return $this->belongsTo(Order::class);
    }

    public function paymentType()
    {
        return $this->belongsTo(PaymentType::class);
    }

    public $timestamps = true;
    protected $dates = ['deleted_at'];
}
