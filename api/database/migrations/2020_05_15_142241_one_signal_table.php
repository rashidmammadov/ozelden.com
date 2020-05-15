<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class OneSignalTable extends Migration {
    /**
     * Run the migrations.
     * @return void
     */
    public function up() {
        Schema::create(DB_ONE_SIGNAL_TABLE, function (Blueprint $table) {
            $table->integer(USER_ID)->references(IDENTIFIER)->on(DB_USERS_TABLE);
            $table->string(ONE_SIGNAL_DEVICE_ID, 100);
            $table->string(DEVICE_TYPE)->nullable();
            $table->string(IP, 20)->nullable();
            $table->boolean(STATUS)->default(1);
            $table->timestamps();
        });
    }


    /**
     * Reverse the migrations.
     * @return void
     */
    public function down() {
        Schema::dropIfExists(DB_ONE_SIGNAL_TABLE);
    }
}
