<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class ProductoProveedor extends Model
{
    public $fillable = [
        'idProveedor',
        'idProducto',
        'codigo',
        'descripcion',
        'medidas',
        'precio'
        ];

    public function purchase_orders(){
        return $this->belongsToMany('App\PurchaseOrder', 'purchaseOrders_productoProveedor','idProductoProveedor','idPO')
            ->withPivot('cantidad_producto')
            ->withTimestamps();
    }

    public function producto(){
        return $this->belongsTo('App\Producto', 'idProducto');
    }   
}
