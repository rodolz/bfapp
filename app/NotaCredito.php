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

}