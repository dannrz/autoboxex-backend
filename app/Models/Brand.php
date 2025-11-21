<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Brand extends Model
{
    protected $table = 'Marca';
    protected $primaryKey = 'IdMarca';

    public $timestamps = false;

    protected $fillable = ['IdMarca', 'Marca'];
}
