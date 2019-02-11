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
            $table->increments(IDENTIFIER);
            $table->char(TYPE, 10);
            $table->char(NAME, 50);
            $table->char(SURNAME, 99);
            $table->bigInteger(BIRTH_DATE);
            $table->char(EMAIL, 99)->unique();
            $table->string(PASSWORD);
            $table->char(SEX, 10);
            $table->boolean(STATE)->default(0);
            $table->string(REMEMBER_TOKEN, 400)->nullable();
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
