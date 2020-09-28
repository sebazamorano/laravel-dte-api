<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateDocumentosIddocTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('documentos_iddoc', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedBigInteger('documento_id');
            $table->string('TipoDTE', 3)->nullable();
            $table->unsignedInteger('Folio')->nullable();
            $table->date('FchEmis');
            $table->tinyInteger('IndNoRebaja')->nullable();
            $table->tinyInteger('TipoDespacho')->nullable();
            $table->tinyInteger('IndTraslado')->nullable();
            $table->string('TpoImpresion', 1)->nullable();
            $table->tinyInteger('IndServicio')->nullable();
            $table->tinyInteger('MntBruto')->nullable();
            $table->tinyInteger('TpoTranCompra')->nullable();
            $table->tinyInteger('TpoTranVenta')->nullable()->default(1);
            $table->tinyInteger('FmaPago')->nullable()->default(2);
            $table->string('FmaPagoExp', 2)->nullable();
            $table->date('FchCancel')->nullable();
            $table->bigInteger('MntCancel')->nullable();
            $table->bigInteger('SaldoInsol')->nullable();
            $table->date('PeriodoDesde')->nullable();
            $table->date('PeriodoHasta')->nullable();
            $table->string('MedioPago', 2)->nullable();
            $table->string('TipoCtaPago', 2)->nullable();
            $table->string('NumCtaPago', 20)->nullable();
            $table->string('BcoPago', 40)->nullable();
            $table->string('TermPagoCdg', 4)->nullable();
            $table->string('TermPagoGlosa', 100)->nullable();
            $table->integer('TermPagoDias')->nullable();
            $table->date('FchVence')->nullable();
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
        Schema::drop('documentos_iddoc');
    }
}
