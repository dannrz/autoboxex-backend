<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasOne;

class Vehicles extends Model
{
    protected $table = 'ClienteVeh';

    public function cliente(): BelongsTo
    {
        return $this->belongsTo(Cliente::class, 'IdCliente', 'IdCliente');
    }

    public function servicio(): BelongsTo
    {
        return $this->belongsTo(Service::class, 'IdVehiculo', 'IdVehiculo');
    }

    public function marca(): HasOne
    {
        return $this->hasOne(Brand::class, 'IdMarca', 'IdMarca');
    }
}
