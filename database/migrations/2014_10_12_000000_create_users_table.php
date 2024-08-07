<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateUsersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('users', function (Blueprint $table) {
            $table->id();
            $table->integer('user_id')->unique();
            $table->string('user_name');
            $table->string('email')->unique();
            $table->timestamp('email_verified_at')->nullable();
            $table->enum('role', ['personal', 'admin_pd', 'operator', 'editor', 'executive', 'administrator']);
            $table->string('password');
            $table->integer('id_perangkat_daerah')->nullable();
            $table->string('perangkat_daerah')->nullable();
            $table->string('status')->nullable();
            $table->integer('login_count')->nullable();
            $table->dateTime('login_time')->nullable();
            $table->dateTime('logout_time')->nullable();
            $table->string('session')->nullable();
            $table->string('entry_user')->nullable();
            $table->dateTime('entry_time')->nullable();
            $table->string('edit_user')->nullable();
            $table->dateTime('edit_time')->nullable();
            $table->rememberToken();
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
        Schema::dropIfExists('users');
    }
}
