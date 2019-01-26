<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class POStatus extends Model
{
    protected $table = 'purchaseorders_status';
    public $fillable = [
    'po_status'
    ];
    
}
