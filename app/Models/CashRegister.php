<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CashRegister extends Model
{
    use HasFactory;
    protected $table = 'cash_registers';
    protected $primaryKey = 'id';

    protected $fillable = [
        'description',
        'balance',
        'status'
    ];

    public function cashRegisterHistories()
    {
        return $this->hasMany(CashRegisterHistory::class);
    }

    public $timestamps = true;
    protected $date = ['deleted_at'];
}