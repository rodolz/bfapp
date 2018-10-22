<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Pago extends Model
{
    public $fillable = [
    'idTipoPago',
    'idCliente',
    'banco',
    'numero_referencia',
    'monto_pago',
    'descripcion',
    'created_at'
    ];


    public function cliente(){
        return $this->belongsTo('App\Cliente', 'idCliente');
    }
    public function facturas(){
        return $this->belongsToMany('App\Factura', 'facturas_pagos','idPago','idFactura')
            ->withPivot('idFactura','idCliente')
            ->withTimestamps();
    }

    public function tipo_pago(){
        return $this->belongsTo('App\TipoPago','idTipoPago');
    }

    // this is a recommended way to declare event handlers
    protected static function boot() {
        parent::boot();

        // Eliminando los records sobrantes de las tablas pivote
        static::deleting(function($pago) {
            $pago->facturas()->detach();
        });
    }
}