<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreatePurchaseordersProductosproveedorTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('purchaseorders_productosproveedor', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('idPO')->unsigned();
            $table->integer('idProductoProveedor')->unsigned();
            $table->integer('cantidad_producto')->unsigned();
            $table->decimal('precio_final',13,2);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('purchaseorders_productosproveedor');
    }
}
