<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class OfferTable extends Migration {
    /**
     * Run the migrations.
     * @return void
     */
    public function up() {
        Schema::create(DB_OFFER_TABLE, function (Blueprint $table) {
            $table->bigIncrements(OFFER_ID);
            $table->integer(SENDER_ID)->references(IDENTIFIER)->on(DB_USERS_TABLE);
            $table->integer(RECEIVER_ID)->references(IDENTIFIER)->on(DB_USERS_TABLE);
            $table->integer(STUDENT_ID)->nullable()->references(STUDENT_ID)->on(DB_STUDENT_TABLE);
            $table->char(SENDER_TYPE, 10);
            $table->integer(TUTOR_LECTURE_ID)->references(TUTOR_LECTURE_ID)->on(DB_TUTOR_LECTURE_TABLE);
            $table->float(OFFER)->nullable();
            $table->char(CURRENCY, 3)->default(TURKISH_LIRA);
            $table->string(DESCRIPTION, 200)->nullable();
            $table->integer(STATUS)->default(OFFER_STATUS_WAITING);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     * @return void
     */
    public function down() {
        Schema::dropIfExists(DB_OFFER_TABLE);
    }
}
