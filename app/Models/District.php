<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class District extends Model
{
    use HasFactory;
    protected $table = 'districts';
    protected $primaryKey = 'id';

    protected $fillable = [
        'name',
        'status'
    ];

    public $timestamps = true;
    protected $dates = ['deleted_at'];

    public function deliveryOptions()
    {
        return $this->belongsToMany(DeliveryOption::class, 'district_delivery_option')->withPivot('price');
    }
}