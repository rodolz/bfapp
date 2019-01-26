<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class PurchaseOrder extends Model
{
    public $fillable = [
        'po_number',
        'idProveedor',
        'idPOStatus',
        'idShipto',
        'tax',
        'comments',
        'po_subtotal',
        'po_total_amount',
        'created_at'
    ];

    public function po_pp(){
        return $this->belongsToMany('App\ProductoProveedor','purchaseorders_productosproveedores','idPO','idProductoProveedor')->withPivot('cantidad_producto','precio_final');
    }
    
    public function proveedor(){
        return $this->belongsTo('App\Proveedor', 'idProveedor');
    }

    public function estado(){
        return $this->belongsTo('App\POStatus', 'idPOStatus');
    }

    public function shipto(){
        return $this->belongsTo('App\Shipto', 'idShipto');
    }
    // this is a recommended way to declare event handlers
    protected static function boot() {
        parent::boot();

        // Eliminando los records sobrantes de las tablas pivote
        static::deleting(function($po) {
            $po->po_pp()->detach();

        });
    }
}
