<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePengirimenTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('pengiriman', function (Blueprint $table) {
            $table->id('id_pengiriman')->unique();
            $table->string('id_usul')->nullable();
            $table->string('nama')->nullable();
            $table->string('nip')->nullable();
            $table->string('nomor_surat');
            $table->string('tgl_surat');
            $table->string('file_spt');
            $table->string('tgl_mulai');
            $table->string('tgl_selesai');
            $table->string('entry_user')->nullable();
            $table->dateTime('entry_time')->nullable();
            $table->string('edit_user')->nullable();
            $table->dateTime('edit_time')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('pengiriman');
    }
}
