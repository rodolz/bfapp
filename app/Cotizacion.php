<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Cotizacion extends Model
{
    public $fillable = [
    'num_cotizacion',
    'idCliente',
    'idUsuario',
    'condicion',
    't_entrega',
    'd_oferta',
    'garantia',
    'monto_cotizacion',
    'notas',
    'itbms'
    ];

	protected static function boot() {
        parent::boot();
        // Eliminando los records sobrantes de las tablas pivote
        static::deleting(function($cotizacion) {
            //En caso de que la orden ya este facturada, se debe borrar la factura asociada a esta
            $cotizacion->cotizacion_producto()->detach();
        });
    }

    public function cliente(){
    	return $this->belongsTo('App\Cliente','idCliente');
    }
    public function cotizacion_producto(){
        return $this->belongsToMany('App\Producto','cotizacion_producto','idCotizacion','idProducto')->withPivot('cantidad_producto','precio_final');
    }

    public function estado(){
        return $this->belongsTo('App\CotizacionesEstado', 'idCotizacionEstado');
    }
}