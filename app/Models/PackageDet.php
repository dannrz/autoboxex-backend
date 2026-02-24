<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\{BelongsTo, HasOne};

class PackageDet extends Model
{
    protected $table = "PaqueteDet";

    public function paquete(): BelongsTo
    {
        return $this->belongsTo(Package::class, 'IdPaquete', 'IdPaquete');
    }

    public function refaccion(): HasOne
    {
        return $this->hasOne(Refaccion::class, 'IdRefaccion', 'IdRefaccion');
    }
}
