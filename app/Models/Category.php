<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Category extends Model
{
    use HasFactory;
    protected $table = 'categories';
    protected $primaryKey = 'id';

    protected $fillable = [
        'name',
        'description',
        'image',
        'status'
    ];

    public $timestamps = true;
    protected $date = ['deleted_at'];
}
