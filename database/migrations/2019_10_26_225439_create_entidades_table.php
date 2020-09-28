<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateEntidadesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('entidades', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedBigInteger('empresa_id');
            $table->string('rut', 11);
            $table->string('nombre', 255)->nullable();
            $table->string('razonSocial', 100);
            $table->boolean('personaJuridica');
            $table->string('telefono', 70)->nullable();
            $table->text('urlLogotipo')->nullable();
            $table->string('contacto', 80)->nullable();
            $table->unsignedBigInteger('saldoCliente')->nullable()->default(0);
            $table->unsignedBigInteger('saldoProveedor')->nullable()->default(0);
            $table->unsignedBigInteger('creditoCliente')->default(0)->nullable();
            $table->boolean('proveedor');
            $table->boolean('cliente');
            $table->timestamps();
            $table->softDeletes();
            $table->foreign('empresa_id')->references('id')->on('empresas');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('entidades');
    }
}
