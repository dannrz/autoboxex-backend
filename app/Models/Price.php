<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Price extends Model
{
    protected $table = 'Precio';
    public $timestamps = false;
    protected $primaryKey = 'IdProducto';
    protected $fillable = ['IdProducto', 'Producto', 'Precio'];
}
