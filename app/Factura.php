<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Factura extends Model
{
    public $fillable = [
    'num_factura',
    'idOrden',
    'idCliente',
    'condicion',
    'idFacturaEstado',
    'num_fiscal',
    'itbms',
    'monto_factura',
    'subtotal',
    'created_at'
    ];

    public function getAmountFormatted()
    {
        return number_format($this->monto_factura,2,'.',',');
    }
	public function cliente(){
    	return $this->belongsTo('App\Cliente', 'idCliente');
    }

	public function orden(){
    	return $this->belongsTo('App\Orden', 'idOrden');
    }

    public function pagos(){
        return $this->belongsToMany('App\Pago', 'facturas_pagos' ,'idFactura' ,'idPago')
            ->withPivot('monto_pago')
            ->withTimestamps();
    }

    public function estado(){
        return $this->belongsTo('App\FacturasEstado', 'idFacturaEstado');
    }
}