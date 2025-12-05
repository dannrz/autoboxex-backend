<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Modelo extends Model
{
    protected $table = 'Modelo';
    public $timestamps = false;
    protected $fillable = ['IdMarca', 'Modelo',];

    public function brand(): BelongsTo
    {
        return $this->belongsTo(Brand::class, 'IdMarca', 'IdMarca');
    }
}
