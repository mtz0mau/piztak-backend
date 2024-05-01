<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    use HasFactory;
    protected $table = 'products';
    protected $primaryKey = 'id';

    protected $fillable = [
        'name',
        'image',
        'description',
        'is_special',
        'sub_category_id',
        'category_id',
        'status'
    ];

    public function sizes()
    {
        return $this->belongsToMany(Size::class)->withPivot('price');
    }

    public function orders()
    {
        return $this->belongsToMany(Order::class, 'order_product')->withPivot(['name', 'unit_price', 'quantity', 'size_id']);
    }

    public $timestamps = true;
    protected $date = ['deleted_at'];
}