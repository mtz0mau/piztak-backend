<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PointCardType extends Model
{
    use HasFactory;
    protected $table = 'point_card_types';
    protected $primaryKey = 'id';

    protected $fillable = [
        'name',
        'discount',
        'status'
    ];

    public $timestamps = true;
    protected $dates = ['deleted_at'];
}
