<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class Pegawai extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('pegawai', function (Blueprint $table) {
            $table->id('id_pegawai')->unique();
            $table->string('nip');
            $table->string('nama_lengkap', 255);
            $table->string('jabatan', 255);
            $table->string('sub_satuan_organisasi', 255);
            $table->string('satuan_organisasi', 255);
            $table->string('perangkat_daerah', 255);
            $table->string('id_perangkat_daerah', 255);
            $table->string('entry_user', 255);
            $table->string('edit_user', 255);
            $table->dateTime('entry_time')->nullable();
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
        Schema::dropIfExists('pegawai');
    }

    
}
