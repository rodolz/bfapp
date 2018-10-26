<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class FacturasPagos extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('facturas_pagos', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('idFactura')->unique()->unsigned();
            $table->integer('idPago')->unsigned()->nullable();
            $table->integer('idCliente')->unsigned()->nullable();
            $table->decimal('monto_pago');
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
        Schema::dropIfExists('facturas_pagos');
    }
}
