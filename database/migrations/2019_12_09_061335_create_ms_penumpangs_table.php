<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateMsPenumpangsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('ms_penumpang', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('jenis',15);
            $table->decimal('harga',6,2);
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
        Schema::dropIfExists('ms_penumpangs');
    }
}
