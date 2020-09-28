<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddGlosaEstadoSiiAndGlosaErrSiiToDocumentos extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('documentos', function (Blueprint $table) {
            $table->string('glosaEstadoSii', '255')->after('estado')->nullable();
            $table->string('glosaErrSii', '255')->after('glosaEstadoSii')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('documentos', function (Blueprint $table) {
            $table->dropColumn('glosaErrSii');
            $table->dropColumn('glosaEstadoSii');
        });
    }
}
