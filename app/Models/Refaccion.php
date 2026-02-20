<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Refaccion extends Model
{
    protected $table = 'Refaccion';
    protected $primaryKey = 'IdRefaccion';
    public $timestamps = false;
    protected $fillable = [
        'IdRefaccion',
        'Refacción',
        'Unidad',
        'Codigo',
        'Tipo',
        'Cantidad',
        'Precio',
        'Fecha',
        'Marca',
        'Calidad',
        'PrecioIva',
    ];
}
