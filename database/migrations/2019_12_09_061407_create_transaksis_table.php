<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTransaksisTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('transaksi', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('no_transaksi',30);
            $table->date('tgl_transaksi');
            $table->time('jam_transaksi');
            $table->integer('id_penumpang');
            $table->integer('id_koridor');
            $table->integer('id_bus');
            $table->integer('id_shelter');
            $table->string('trip_a',10);
            $table->string('trip_b',10);
            $table->integer('shift');
            $table->string('username');
            $table->string('opsi_bayar',20);
            $table->decimal('harga',6,2);
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
        Schema::dropIfExists('transaksis');
    }
}
