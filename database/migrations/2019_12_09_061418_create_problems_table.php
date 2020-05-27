<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateProblemsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('problems', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->integer('id_koridor');
            $table->integer('id_bus');
            $table->text('keterangan');
            $table->string('username',25);
            $table->string('longitude',25);
            $table->string('latitude',25);
            $table->string('trip',5);
            $table->integer('shift');
            $table->tinyInteger('status')->default(0)->comment('0:belum dilihat, 1:sudah dilihat');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('problems');
    }
}
