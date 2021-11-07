<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateSlidepoolTbl extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('slidepool_tbl', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedInteger('childknotid');
            $table->foreign('childknotid')->references('id')->on('treeviewchildknots_tbl');
            $table->integer('pagnumber');
            $table->string('childknotname',200);
            $table->string('childknotpath',200);
            $table->string('headline',500);
           
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('slidepool_tbl');
    }
}
