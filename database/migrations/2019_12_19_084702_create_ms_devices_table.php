<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateMsDevicesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('ms_devices', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('nama',10)->unique();
            $table->string('imei',20);
            $table->string('kode',5)->nullable();
            $table->tinyInteger('status')->comment('1:aktif, 0:nonaktif')->default(1);
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
        Schema::dropIfExists('ms_devices');
    }
}
