<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

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
            $table->increments('id');
            $table->char('type', 50);
            $table->char('name', 50);
            $table->char('surname', 99);
            $table->bigInteger('birthDate');
            $table->char('email', 99)->unique();
            $table->string('password');
            $table->char('sex', 10);
            $table->boolean('state')->default(0);
            $table->string('remember_token', 400)->nullable();
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
