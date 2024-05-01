<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Size extends Model
{
    use HasFactory;
    protected $table = 'sizes';
    protected $primaryKey = 'id';

    protected $fillable = [
        'name',
        'description',
        'status',
        'category_id'
    ];

    public function extraIngredients()
    {
        return $this->belongsToMany(ExtraIngredient::class, 'size_extra_ingredient')->withPivot(['price']);
    }

    public function products(){
        return $this->belongsToMany(Product::class);
    }

    public $timestamps = true;
    protected $date = ['deleted_at'];
}
