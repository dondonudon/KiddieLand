<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateUserProfilesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('user_profile', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('username',100);
            $table->text('no_sk');
            $table->text('no_spk');
            $table->string('tempat_lahir',15);
            $table->date('tgl_lahir');
            $table->string('jenis_kelamin',1);
            $table->text('alamat');
            $table->string('jabatan',20);
            $table->string('agama',11);
            $table->string('no_ktp',20);
            $table->string('tingkat_pendidikan',5);
            $table->string('jurusan',25);
            $table->text('keterangan')->nullable();
            $table->string('foto',25)->nullable();
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
        Schema::dropIfExists('user_profiles');
    }
}
