<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateDocumentosTotalesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('documentos_totales', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedBigInteger('documento_id');
            $table->string('TpoMoneda', 15)->nullable();
            $table->decimal('MntNeto', 22, 4)->nullable()->default(0);
            $table->decimal('MntExe', 22, 4)->nullable()->default(0);
            $table->decimal('TasaIVA', 5, 2)->nullable();
            $table->bigInteger('IVA')->nullable()->default(0);
            $table->bigInteger('IVAProp')->nullable();
            $table->bigInteger('IVATerc')->nullable();
            $table->bigInteger('IVANoRet')->nullable();
            $table->decimal('MntTotal', 22, 4)->default(0);
            $table->bigInteger('MontoNF')->nullable();
            $table->bigInteger('MontoPeriodo')->nullable();
            $table->bigInteger('SaldoAnterior')->nullable();
            $table->bigInteger('VlrPagar')->nullable();
            $table->softDeletes();
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
        Schema::drop('documentos_totales');
    }
}
