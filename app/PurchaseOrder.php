<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class PurchaseOrder extends Model
{
    public $fillable = [
        'po_number',
        'created_at'
    ];

    public function po_pp(){
        return $this->belongsToMany('App\ProductoProveedor','purchaseOrders_productoProveedor','idPO','idProductoProveedor');
    }
}
