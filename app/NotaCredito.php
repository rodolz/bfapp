<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class NotaCredito extends Model
{
    public $fillable = [
    'num_nota_credito',
    'num_fiscal',
    'idPago',
    'created_at',
    'updated_at'
    ];


    public function pago(){
        return $this->belongsTo('App\Pago', 'idPago');
    }

    // this is a recommended way to declare event handlers
    protected static function boot() {
        parent::boot();

        // Eliminando los records sobrantes de las tablas pivote
        static::deleting(function($nota_credito) {
            $nota_credito->pago()->delete();
        });
    }
}