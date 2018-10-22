<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Cliente extends Model
{
    public $fillable = [
    'empresa',
    'contacto',
    'tel_local',
    'tel_celular',
    'direccion',
    'email',
    'www',
    'ruc'
    ];

    public function ordenes(){
    return $this->hasMany('App\Orden', 'idCliente');
    }
}