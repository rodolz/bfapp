<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateCotizacionsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('cotizacions', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('num_cotizacion')->nullable()->unsigned();
            $table->integer('idCliente')->nullable()->unsigned();
            $table->integer('idUsuario')->nullable()->unsigned();
            $table->string('condicion');
            $table->string('t_entrega');
            $table->string('d_oferta');
            $table->string('garantia');
            $table->string('nota');
            $table->integer('itbms');
            $table->decimal('monto_cotizacion',13,2);
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
        Schema::dropIfExists('cotizacions');
    }
}
