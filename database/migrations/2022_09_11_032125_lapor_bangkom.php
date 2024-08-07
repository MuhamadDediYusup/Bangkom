<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class LaporBangkom extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('laporan', function (Blueprint $table) {
            $table->id('id_lapor')->unique();
            $table->string('id_usul');
            $table->string('nip');
            $table->string('nama');
            $table->string('jenis_diklat');
            $table->string('sub_jenis_diklat');
            $table->string('nama_diklat');
            $table->string('rumpun_diklat');
            $table->string('tempat_diklat')->nullable();
            $table->string('penyelenggara_diklat')->nullable();
            $table->string('lama_pendidikan');
            $table->string('tahun_angkatan')->nullable();
            $table->string('tahun_mulai')->nullable();
            $table->string('tahun_selesai')->nullable();
            $table->string('nomor_sttpp');
            $table->char('status')->nullable();
            $table->text('alasan')->nullable();
            $table->date('tgl_sttpp');
            $table->string('file_spt')->nullable();
            $table->string('file_sttpp')->nullable();
            $table->string('entry_user')->nullable();
            $table->dateTime('entry_time')->nullable();
            $table->string('edit_user')->nullable();
            $table->dateTime('edit_time')->nullable();
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
        Schema::dropIfExists('laporan');
    }
}
