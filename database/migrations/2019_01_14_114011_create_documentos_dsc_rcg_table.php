<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateDocumentosDscRcgTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('documentos_dsc_rcg', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedBigInteger('documento_id');
            $table->tinyInteger('NroLinDR');
            $table->string('TpoMov', 1);
            $table->string('GlosaDR', 45)->nullable();
            $table->string('TpoValor', 1)->nullable();
            $table->decimal('ValorDR', 18, 2)->nullable();
            $table->tinyInteger('IndExeDR')->nullable();
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
        Schema::drop('documentos_dsc_rcg');
    }
}
