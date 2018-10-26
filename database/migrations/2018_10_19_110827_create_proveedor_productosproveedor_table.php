<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateProveedorProductosproveedorTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('proveedores_productosproveedor', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('idProveedor')->unsigned();
            $table->integer('idProductoProveedor')->unsigned();
            $table->integer('cantidad_producto');

            $table->foreign('idProveedor')
                    ->references('id')
                    ->on('proveedors')
                    ->onUpdate('CASCADE')
                    ->onDelete('CASCADE');
                    
            $table->foreign('idProductoProveedor')
                    ->references('id')
                    ->on('producto_proveedors')
                    ->onUpdate('CASCADE')
                    ->onDelete('CASCADE');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('proveedores_productosproveedor', function (Blueprint $table) {
            //
        });
    }
}
