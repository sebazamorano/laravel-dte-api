<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateFoliosConsumptionSummariesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('sii_folios_consumption_summaries', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedBigInteger('sii_folio_consumption_id');
            $table->integer('tipo_documento');
            $table->unsignedBigInteger('mnt_neto')->nullable();
            $table->unsignedBigInteger('mnt_iva')->nullable();
            $table->decimal('tasa_IVA', 5, 2)->nullable();
            $table->unsignedBigInteger('mnt_exento')->nullable();
            $table->unsignedBigInteger('mnt_total')->nullable();
            $table->unsignedBigInteger('folios_emitidos')->nullable();
            $table->unsignedBigInteger('folios_anulados')->nullable();
            $table->unsignedBigInteger('folios_utilizados')->nullable();
            $table->timestamps();
            $table->softDeletes();

            $table->foreign('sii_folio_consumption_id', 'folio_consumption_folio_consumption_summaries')->references('id')->on('sii_folios_consumption');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('sii_folios_consumption_summaries');
    }
}
