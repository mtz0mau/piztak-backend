<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ExtraIngredient extends Model
{
    use HasFactory;
    protected $table = 'extra_ingredients';
    protected $primaryKey = 'id';
    
    protected $fillable = [
        'name',
        'image',
        'status',
        'category_id'
    ];

    public function sizes()
    {
        return $this->belongsToMany(Size::class);
    }

    public $timestamps = true;
    protected $date = ['deleted_at'];
}
