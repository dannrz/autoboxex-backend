<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Tool extends Model
{
    use HasFactory;

    protected $table = 'tools';

    protected $fillable = [
        'name',
        'sku',
        'quantity',
        'description',
        'last_modify',
    ];

    protected $hidden = [
        'created_at',
        'updated_at',
    ];
}
