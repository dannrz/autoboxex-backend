<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class InOut extends Model
{
    protected $table = 'EntSalDet';

    public function refaccion()
    {
        return $this->belongsTo(Refaccion::class, 'IdRefaccion', 'IdRefaccion');
    }
}
