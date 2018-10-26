<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateFacturasTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('facturas', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('num_factura')->unique()->unsigned();
            $table->integer('idOrden')->unique()->unsigned();
            $table->integer('idCliente')->unsigned();
            $table->string('condicion');
            $table->integer('itbms');
            $table->decimal('monto_factura',13,2);
            $table->integer('idFacturaEstado')->unsigned();
            $table->integer('num_fiscal')->unsigned()->default(0);
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
        Schema::dropIfExists('facturas');
    }
}
