<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Comision extends Model
{
    public $fillable = [
    'num_comision',
    'idRepartidor',
    'monto_comision',
    'created_at'
    ];

    public function repartidor(){
    	return $this->belongsTo('App\User','idRepartidor');
    }

    public function ordenes(){
  		//withPivot() para poder acceder a los pocentajes de cada comision
    	return $this->belongsToMany('App\Orden','ordenes_comisiones','idComision','idOrden')->withPivot('porcentaje');
    }
}