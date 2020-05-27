<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateMasterSheltersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('master_shelter', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->integer('id_koridor');
            $table->string('nama',25);
            $table->string('lokasi',50);
            $table->string('longitude',25)->default(0);
            $table->string('latitude',25)->default(0);
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
        Schema::dropIfExists('master_shelters');
    }
}
