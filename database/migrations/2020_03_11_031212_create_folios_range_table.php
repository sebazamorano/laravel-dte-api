<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateFoliosRangeTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('sii_folios_range', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedBigInteger('sii_folio_consumption_summary_id');
            $table->unsignedBigInteger('inicial');
            $table->unsignedBigInteger('final');
            $table->boolean('utilizados')->default(true);
            $table->timestamps();
            $table->softDeletes();

            $table->foreign('sii_folio_consumption_summary_id')->references('id')->on('sii_folios_consumption_summaries');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('sii_folios_range');
    }
}
