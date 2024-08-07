<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class UsulBangkom extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('usulan', function (Blueprint $table) {
            $table->id('id_usul')->unique();
            $table->string('nip')->nullable();
            $table->string('nama')->nullable();
            $table->string('jenis_diklat');
            $table->string('sub_jenis_diklat');
            $table->string('rumpun_diklat');
            $table->string('nama_diklat');
            $table->enum('status',[NULL,0,1,9])->default(NULL)->nullable();
            $table->string('alasan')->nullable();
            $table->string('entry_user')->nullable();
            $table->dateTime('entry_time')->nullable();
            $table->string('edit_user')->nullable();
            $table->dateTime('edit_time')->nullable();
            // $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('usulan');
    }
}
