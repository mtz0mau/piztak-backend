<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Address extends Model
{
    use HasFactory;
    protected $table = 'addresses';
    protected $primaryKey = 'id';

    protected $fillable = [
        'street',
        'street_number',
        'interior_number',
        'postal_code',
        'is_primary',
        'references',
        'customer_id',
        'district_id',
        'status'
    ];

    public function customer()
    {
        return $this->belongsTo(Customer::class);
    }

    public function district()
    {
        return $this->belongsTo(District::class);
    }

    protected static function boot()
    {
        parent::boot();

        static::creating(function ($address) {
            $address->is_primary = true;
            $customer = Customer::find($address->customer_id);
            foreach ($customer->addresses as $a) {
                if($a->is_primary) $address->is_primary = false;
            }
        });
    }

    public $timestamps = true;
    protected $dates = ['deleted_at'];
}