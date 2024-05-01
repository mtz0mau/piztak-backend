<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PointCardHistory extends Model
{
    use HasFactory;
    protected $table = 'point_card_histories';
    protected $primaryKey = 'id';

    protected $fillable = [
        'amount',
        'previous_amount',
        'action',
        'description',
        'status',
        'point_card_id'
    ];

    public function pointCard()
    {
        return $this->belongsTo(PointCard::class);
    }

    public $timestamps = true;
    protected $date = ['deleted_at'];
}
