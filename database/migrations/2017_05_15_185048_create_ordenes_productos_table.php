<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class OrdenesProductos extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('ordenes_productos', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('idOrden')->unsigned();
            $table->integer('idProducto')->unsigned();
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
        Schema::dropIfExists('ordenes_productos');
    }
}
