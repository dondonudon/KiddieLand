<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateMsPembayaransTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('ms_pembayaran', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('nama',25)->unique();
            $table->text('keterangan')->nullable();
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
        Schema::dropIfExists('ms_pembayarans');
    }
}
