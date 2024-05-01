<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SubCategory extends Model
{
    use HasFactory;
    protected $table = 'sub_categories';
    protected $primaryKey = 'id';

    protected $fillable = [
        'name',
        'description',
        'image',
        'status',
        'category_id'
    ];

    public function Category()
    {
        return $this->belongsTo(Category::class);
    }

    public $timestapms = true;
    protected $date = ['deleted_at'];
}