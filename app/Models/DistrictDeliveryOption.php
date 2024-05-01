<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DistrictDeliveryOption extends Model
{
    use HasFactory;

    protected $table = 'district_delivery_option';

    public function district()
    {
        return $this->belongsTo(District::class);
    }

    public function deliveryOption()
    {
        return $this->belongsTo(DeliveryOption::class);
    }

    public function getPrice($value)
    {
        return (float) $value;
    }
}
