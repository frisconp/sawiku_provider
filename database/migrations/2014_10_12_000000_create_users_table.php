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
            $table->string('name');
            $table->date('date_of_birth')->nullable();
            $table->string('city')->nullable();
            $table->text('address')->nullable();
            $table->string('phone_number');
            $table->string('email')->unique();
            $table->timestamp('email_verified_at')->nullable();
            $table->string('password')->nullable();
            $table->text('avatar')->nullable();
            $table->integer('balance')->default(0);
            $table->unsignedBigInteger('role_id')->default(1);
            $table->string('google_account_id')->nullable();
            $table->string('gopay_account_id')->nullable();
            $table->timestamp('last_access_at')->nullable();
            $table->rememberToken();
            $table->timestamps();

            $table->foreign('role_id')->references('id')->on('roles');
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
