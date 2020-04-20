<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class TutorStudent extends Migration {
    /**
     * Run the migrations.
     * @return void
     */
    public function up() {
        Schema::create(DB_TUTOR_STUDENT_TABLE, function (Blueprint $table) {
            $table->integer(TUTOR_ID)->references(IDENTIFIER)->on(DB_USERS_TABLE);
            $table->integer(USER_ID)->references(IDENTIFIER)->on(DB_USERS_TABLE);
            $table->integer(STUDENT_ID)->nullable()->references(STUDENT_ID)->on(DB_STUDENT_TABLE);
            $table->integer(OFFER_ID)->nullable()->references(OFFER_ID)->on(DB_OFFER_TABLE);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     * @return void
     */
    public function down() {
        Schema::dropIfExists(DB_TUTOR_STUDENT_TABLE);
    }
}
