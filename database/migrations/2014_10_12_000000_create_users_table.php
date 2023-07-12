<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
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
            $table->string('user_name')->unique();
            $table->string('first_name');
            $table->string('last_name');
            $table->string('email')->unique();
            $table->string('img',255);
            $table->tinyInteger('height');
            $table->tinyInteger('weight');
            $table->tinyInteger('foot_size');
            $table->timestamp('email_verified_at')->nullable();
            $table->foreignId('role_id')->constrained('roles')->onDelete('CASCADE');
            $table->string('password');
            $table->string('access_token',64)->nullable();
            $table->integer('otp')->nullable();
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
};
