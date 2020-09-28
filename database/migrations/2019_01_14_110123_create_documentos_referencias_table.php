<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateDocumentosReferenciasTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('documentos_referencias', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedBigInteger('documento_id');
            $table->tinyInteger('NroLinRef');
            $table->string('TpoDocRef', 3);
            $table->tinyInteger('IndGlobal')->nullable();
            $table->string('FolioRef', 18);
            $table->date('FchRef');
            $table->tinyInteger('CodRef')->nullable();
            $table->string('RazonRef', 90)->nullable();
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
        Schema::drop('documentos_referencias');
    }
}
