<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AnnouncementTable extends Migration {

    /**
     * Run the migrations.
     * @return void
     */
    public function up() {
        Schema::create(DB_ANNOUNCEMENT_TABLE, function (Blueprint $table) {
            $table->increments(ANNOUNCEMENT_ID);
            $table->integer(TUTORED_ID)->references(IDENTIFIER)->on(DB_USERS_TABLE);
            $table->integer(STUDENT_ID)->nullable()->references(STUDENT_ID)->on(DB_STUDENT_TABLE);
            $table->string(LECTURE_AREA, 100)->references(LECTURE_AREA)->on(DB_LECTURE_TABLE);
            $table->string(LECTURE_THEME, 100)->nullable()->references(LECTURE_THEME)->on(DB_LECTURE_TABLE);
            $table->string(CITY, 20)->references(CITY_NAME)->on(DB_CITY_TABLE);
            $table->string(DISTRICT, 30)->nullable()->references(DISTRICT_NAME)->on(DB_CITY_TABLE);
            $table->float(MIN_PRICE)->nullable();
            $table->float(MAX_PRICE)->nullable();
            $table->char(CURRENCY, 3)->default(TURKISH_LIRA);
            $table->char(SEX, 10)->nullable();
            $table->integer(STATUS)->default(ANNOUNCEMENT_STATUS_ACTIVE);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     * @return void
     */
    public function down() {
        Schema::dropIfExists(DB_ANNOUNCEMENT_TABLE);
    }
}
