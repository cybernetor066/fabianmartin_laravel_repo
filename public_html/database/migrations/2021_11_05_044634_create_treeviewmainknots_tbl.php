<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTreeviewmainknotsTbl extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('treeviewmainknots_tbl', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('mainknotname',200);
            $table->string('mainknotpath',200);
            $table->boolean('vertriebsgespraech');
            $table->boolean('strategiegespraech');
            $table->boolean('planungsworkshop');
            $table->boolean('planungsgespraech');
            $table->boolean('investmentprozessgespraech');
            $table->boolean('folienpool');
            $table->string('changedon',200);

        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('treeviewmainknots_tbl');
    }
}
