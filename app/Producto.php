<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Producto extends Model
{
    public $fillable = [
    'idCategoria',
    'codigo',
    'descripcion',
    'medidas',
    'precio',
    'precio_costo',
    'cantidad'
    ];

	public function categoria(){
    	return $this->belongsTo('App\Categoria', 'idCategoria');
    }

    public function ordenes(){
        return $this->belongsToMany('App\Orden', 'ordenes_productos','idProducto','idOrden')
            ->withPivot('cantidad_producto','precio_final')
            ->withTimestamps();
    }
}