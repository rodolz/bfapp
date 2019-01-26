<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Shipto extends Model
{
  
    public $fillable = [
    'nombre_shipto',
    'name',
    'address_line1',
    'address_line2',
    'country',
    'state',
    'city',
    'phone'
    ];

  public function pos(){
    return $this->hasMany('App\PurchaseOrder','idShipto');
  }
  
}
