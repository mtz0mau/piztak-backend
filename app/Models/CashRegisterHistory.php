<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CashRegisterHistory extends Model
{
    use HasFactory;
    protected $table = 'cash_register_histories';
    protected $primaryKey = 'id';

    protected $fillable = [
        'amount',
        'action',
        'description',
        'previous_amount',
        'cash_register_id',
        'user_id',
        'status'
    ];

    public function cashRegister()
    {
        return $this->belongsTo(CashRegister::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public $timestamps = true;
    protected $date = ['deleted_at'];
}
