<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Proveedor extends Model
{
    public $fillable = [
        'name',
        'email',
        'website',
        'address',
        'country',
        'city',
        'postcode'
        ];

        public function productos_proveedor(){
            return $this->hasMany('App\ProductoProveedor','idProveedor');
        }
}
