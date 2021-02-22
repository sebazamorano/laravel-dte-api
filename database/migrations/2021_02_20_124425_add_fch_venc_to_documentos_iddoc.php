<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddFchVencToDocumentosIddoc extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('documentos_iddoc', function (Blueprint $table) {
            $table->date('FchVenc')->nullable()->after('FchVence');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('documentos_iddoc', function (Blueprint $table) {
            $table->dropColumn(['FchVenc']);
        });
    }
}
