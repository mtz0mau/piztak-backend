<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PointCard extends Model
{
    use HasFactory;
    protected $table = 'point_cards';
    protected $primaryKey = 'id';

    protected $fillable = [
        'points',
        'status',
        'point_card_type_id',
        'serial'
    ];

    public function pointCardType()
    {
        return $this->belongsTo(PointCardType::class);
    }

    public $timestapms = true;
    protected $date = ['deleted_at'];
}