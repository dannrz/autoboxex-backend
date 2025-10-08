<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasOne;

class Service extends Model
{
    protected $table = 'Servicio';

    public function cliente(): BelongsTo
    {
        return $this->belongsTo(Cliente::class, 'IdCliente', 'IdCliente');
    }

    public function vehiculo(): HasOne
    {
        return $this->hasOne(Vehicles::class, 'IdVehiculo', 'IdVehiculo');
    }
}
