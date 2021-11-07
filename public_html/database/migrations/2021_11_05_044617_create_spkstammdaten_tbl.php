<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateSpkstammdatenTbl extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('spkstammdaten_tbl', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('spkname',200);
            $table->string('spkblz',200);
            $table->string('spkregion',200);
            $table->string('spkverband',200);
            $table->string('strategiegespraechchangedon',200)->nullable($value = true);
            $table->string('planungsworkshopchangedon',200)->nullable($value = true);
            $table->string('planungsgespraechchangedon',200)->nullable($value = true);
            $table->string('investmentprozessgespraechchangedon',200)->nullable($value = true);
            $table->string('bnumbervd',200)->nullable($value = true);
            $table->string('bnumbervb1',200)->nullable($value = true);
            $table->string('bnumbervb2',200)->nullable($value = true);
            $table->string('bnumbervb3',200)->nullable($value = true);
            $table->string('bnumbervb4',200)->nullable($value = true);
            $table->string('changedon',200)->nullable($value = true);

        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('spkstammdaten_tbl');
    }
}
