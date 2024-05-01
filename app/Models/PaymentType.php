<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PaymentType extends Model
{
    use HasFactory;
    protected $table = 'payment_types';
    protected $primaryKey = 'id';

    protected $fillable = [
        'name',
        'description',
        'card_accepted',
        'cash_accepted',
        'status'
    ];

    public $timestamps = true;
    protected $date = ['deleted_at'];
}
