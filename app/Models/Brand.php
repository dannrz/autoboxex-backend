<?php

namespace App\Models;

use Illuminate\Database\Eloquent\{Relations\HasMany, Model};

class Brand extends Model
{
    protected $table = 'Marca';
    protected $primaryKey = 'IdMarca';
    public $incrementing = false;

    public $timestamps = false;

    protected $fillable = ['IdMarca', 'Marca'];

    public function modelos(): HasMany
    {
        return $this->hasMany(Modelo::class, 'IdMarca', 'IdMarca');
    }
}
