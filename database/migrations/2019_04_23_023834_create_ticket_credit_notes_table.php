<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateTicketCreditNotesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('ticket_credit_notes', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedBigInteger('empresa_id')->nullable();
            $table->unsignedBigInteger('documento_id')->nullable();
            $table->integer('tipoBoleta')->comment('39 o 41 ');
            $table->date('fechaEmision');
            $table->integer('montoNeto')->default(0);
            $table->integer('montoExento')->default(0);
            $table->integer('iva')->default(0);
            $table->decimal('tasaIva', 5, 2)->nullable();
            $table->integer('montoTotal')->default(0);
            $table->integer('folioBoleta');
            $table->integer('folioNotaCredito');
            $table->timestamps();
            $table->softDeletes();
            $table->foreign('empresa_id')->references('id')->on('empresas');
            $table->foreign('documento_id')->references('id')->on('documentos');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::drop('ticket_credit_notes');
    }
}
