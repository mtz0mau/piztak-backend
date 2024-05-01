<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DeliveryOption extends Model
{
    use HasFactory;
    protected $table = 'delivery_options';
    protected $primaryKey = 'id';

    protected $fillable = [
        'name',
        'is_primary',
        'allow_delivery',
        'description',
        'status',
    ];

    public $timestamps = true;
    protected $dates = ['deleted_at'];

    public function districts()
    {
        return $this->belongsToMany(District::class, 'district_delivery_option');
    }
}
