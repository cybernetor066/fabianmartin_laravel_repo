<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTrackinganalyseTbl extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('trackingdownload_tbl', function (Blueprint $table) {

            $table->bigIncrements('id');
            $table->string('downloadtyp',200);
            $table->string('spkvertriebsregion',200);
            $table->string('month',200);
            $table->string('year',200);
            
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('trackinganalyse_tbl');
    }
}
