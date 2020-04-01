<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateUsersTable extends Migration {
    /**
     * Run the migrations.
     * @return void
     */
    public function up() {
        Schema::create(DB_USERS_TABLE, function (Blueprint $table) {
            $table->increments(IDENTIFIER);
            $table->char(TYPE, 10);
            $table->char(NAME, 100);
            $table->char(SURNAME, 100);
            $table->char(BIRTHDAY, 15);
            $table->char(EMAIL, 100)->unique();
            $table->string(IDENTITY_NUMBER, 11);
            $table->string(PASSWORD);
            $table->char(SEX, 10);
            $table->boolean(STATE)->default(USER_STATE_ACTIVE);
            $table->string(REMEMBER_TOKEN, 400)->nullable();
            $table->string(ONESIGNAL_DEVICE_ID, 50)->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     * @return void
     */
    public function down() {
        Schema::dropIfExists(DB_USERS_TABLE);
    }
}
