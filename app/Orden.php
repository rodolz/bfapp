<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Orden extends Model
{
    public $fillable = [
    'num_orden',
    'idCliente',
    'idUsuario',
    'monto_orden',
    'idOrdenEstado',
    'created_at'
    ];


	public function cliente(){

    	return $this->belongsTo('App\Cliente', 'idCliente');
  	}

    public function repartidores(){
        return $this->hasMany('App\Repartidor', 'idOrden');
    }

    // public function ordenes_repartidores(){
    //     return $this->belongsToMany('App\Repartidor','ordenes_repartidores','idOrden','idRepartidor');
    // }

    public function ordenes_repartidores(){
        return $this->belongsToMany('App\User','ordenes_repartidores','idOrden','idRepartidor');
    }

    public function ordenes_productos(){
        return $this->belongsToMany('App\Producto','ordenes_productos','idOrden','idProducto')->withPivot('cantidad_producto','precio_final');
    }

    public function factura(){
        return $this->hasOne('App\Factura', 'idOrden');
    }

    public function estado(){
        return $this->belongsTo('App\OrdenesEstado', 'idOrdenEstado');
    }
    // this is a recommended way to declare event handlers
    protected static function boot() {
        parent::boot();

        // Eliminando los records sobrantes de las tablas pivote
        static::deleting(function($orden) {
            //En caso de que la orden ya este facturada, se debe borrar la factura asociada a esta
            if($orden->factura){
                $factura = Factura::findorFail($orden->factura->id)->delete();
            }
            $orden->ordenes_repartidores()->detach();
            $orden->ordenes_productos()->detach();

        });
    }
}