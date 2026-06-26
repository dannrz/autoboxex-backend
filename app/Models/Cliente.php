<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Cliente extends Model
{
    protected $table = 'Cliente';
    protected $primaryKey = 'IdCliente';
    public $timestamps = false;
    public $incrementing = false;

    protected $fillable = [
        'IdCliente', 'Nombre', 'RFC', 'CP', 'eMail', 'Direccion', 'Colonia',
        'Poblacion', 'Estado', 'Contacto', 'Sucursal', 'Credito',
        'Telefono', 'Telefono2', 'Telefono3', 'Descuento', 'ManoObra',
    ];

    protected $casts = [
        'Credito'   => 'integer',
        'Descuento' => 'float',
        'ManoObra'  => 'float',
    ];

    public function servicios(): HasMany
    {
        return $this->hasMany(Service::class, 'IdCliente', 'IdCliente');
    }

    public function vehiculos(): HasMany
    {
        return $this->hasMany(Vehicles::class, 'IdCliente', 'IdCliente');
    }
}
