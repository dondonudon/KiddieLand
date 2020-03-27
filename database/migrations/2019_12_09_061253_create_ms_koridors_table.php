<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateMsKoridorsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('ms_koridor', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('koridor',25);
            $table->string('rute',100);
            $table->string('trip_a',50);
            $table->string('trip_b',50);
            $table->string('longitude_a',25);
            $table->string('latitude_a',25);
            $table->string('longitude_b',25);
            $table->string('latitude_b',25);
            $table->tinyInteger('status')->default(1)->comment('0:nonaktif, 1:aktif');
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
        Schema::dropIfExists('ms_koridors');
    }
}
