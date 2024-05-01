<?php

namespace App\Models;

use App\Helpers\Helper;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Coupon extends Model
{
    use HasFactory;
    protected $table = 'coupons';
    protected $primaryKey = 'id';

    protected $fillable = [
        'code',
        'discount',
        'type_discount',
        'count',
        'limit',
        'description',
        'status',
        'user_id'
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function generateCouponCode() {
        $this->code = 'PIZ' . Helper::generateCode();
    }
}