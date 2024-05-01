<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Customer extends Model
{
    use HasFactory;
    protected $table = 'customers';
    protected $primaryKey = 'id';

    protected $fillable = [
        'firstname',
        'lastname',
        'phone_number',
        'email',
        'birthdate',
        'status',
        'point_card_id',
        'client_flag_id',
        'whatsapp_msg'
    ];

    public function pointCard()
    {
        return $this->belongsTo(PointCard::class);
    }

    public function clientFlag()
    {
        return $this->belongsTo(ClientFlag::class);
    }

    public function addresses()
    {
        return $this->hasMany(Address::class, 'customer_id');
    }

    public $timestamps = true;
    protected $dates = ['deleted_at'];
}