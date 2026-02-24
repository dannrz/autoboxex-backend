<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Package extends Model
{
    protected $table = "Paquete";

    public function refacciones(): HasMany
    {
        return $this->hasMany(PackageDet::class, 'IdPaquete', 'IdPaquete');
    }
}
