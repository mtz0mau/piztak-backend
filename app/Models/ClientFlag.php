<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ClientFlag extends Model
{
    use HasFactory;
    protected $table = 'client_flags';
    protected $primaryKey = 'id';

    protected $fillable = [
        'name',
        'description',
        'status'
    ];

    public $timestamps = true;
    protected $dates = ['deleted_at'];
}