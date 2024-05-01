<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SizeExtraIngredient extends Model
{
    use HasFactory;
    protected $table = 'size_extra_ingredient';

    public function size()
    {
        return $this->belongsTo(Size::class);
    }

    public function extraIngredient()
    {
        return $this->belongsTo(ExtraIngredient::class);
    }

    public function getPrice($value)
    {
        return (float) $value;
    }
}
