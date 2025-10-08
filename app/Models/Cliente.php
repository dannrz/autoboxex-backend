<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Cliente extends Model
{
    protected $table = 'Cliente';

    public function servicios(): HasMany
    {
        return $this->hasMany(Service::class, 'IdCliente', 'IdCliente');
    }
}
