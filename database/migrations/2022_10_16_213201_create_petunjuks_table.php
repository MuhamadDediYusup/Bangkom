<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePetunjuksTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('petunjuk', function (Blueprint $table) {
            $table->id('id_petunjuk')->unique();
            $table->string('file_petunjuk');
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
        Schema::dropIfExists('petunjuk');
    }
}
