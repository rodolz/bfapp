<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class PurchaseOrder extends Model
{
    public $fillable = [
        'po_number',
        'idProveedor',
        'shipping_method',
        'tax',
        'po_subtotal',
        'po_total_amount',
        'created_at'
    ];

    public function po_pp(){
        return $this->belongsToMany('App\ProductoProveedor','purchaseOrders_productoProveedor','idPO','idProductoProveedor');
    }
}
