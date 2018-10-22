<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class ProductoProveedor extends Model
{
    public $fillable = [
        'idProveedor',
        'codigo',
        'descripcion',
        'medidas',
        'precio'
        ];
}
