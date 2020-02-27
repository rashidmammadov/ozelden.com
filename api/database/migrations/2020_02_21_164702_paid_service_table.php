<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class PaidServiceTable extends Migration {
    /**
     * Run the migrations.
     * @return void
     */
    public function up() {
        Schema::create(DB_PAID_SERVICE_TABLE, function (Blueprint $table) {
            $table->increments(PAID_SERVICE_ID);
            $table->integer(TUTOR_ID)->unique()->references(IDENTIFIER)->on(DB_USERS_TABLE);
            $table->integer(BID)->nullable();
            $table->string(BOOST)->nullable();
            $table->string(RECOMMEND)->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     * @return void
     */
    public function down() {
        Schema::dropIfExists(DB_PAID_SERVICE_TABLE);
    }
}
