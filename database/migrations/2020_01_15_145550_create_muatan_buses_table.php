<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateMuatanBusesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('muatan_bus', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->tinyInteger('id_koridor');
            $table->tinyInteger('id_shelter');
            $table->tinyInteger('id_bus');
            $table->string('username',25);
            $table->tinyInteger('shift');
            $table->date('tanggal');
            $table->tinyInteger('tipe')->comment('1:tiket manual, 2:e-ticketing');
            $table->tinyInteger('arah')->comment('1:naik, 2:turun')->default(1);
            $table->integer('total');
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
        Schema::dropIfExists('muatan_buses');
    }
}
