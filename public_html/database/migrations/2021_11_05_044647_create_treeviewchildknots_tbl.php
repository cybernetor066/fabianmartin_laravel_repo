<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTreeviewchildknotsTbl extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('treeviewchildknots_tbl', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedInteger('mainknotid');
            $table->foreign('mainknotid')->references('id')->on('treeviewmainknots_tbl');
            $table->string('childknotname',200);
            $table->string('childknotpath',200);
            $table->integer('numberofpages');
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
        Schema::dropIfExists('treeviewchildknots_tbl');
    }
}
