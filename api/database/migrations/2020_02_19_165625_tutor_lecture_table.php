<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class TutorLectureTable extends Migration {
    /**
     * Run the migrations.
     * @return void
     */
    public function up() {
        Schema::create(DB_TUTOR_LECTURE_TABLE, function (Blueprint $table) {
            $table->increments(TUTOR_LECTURE_ID);
            $table->integer(TUTOR_ID);
            $table->string(LECTURE_AREA, 100);
            $table->string(LECTURE_THEME, 100);
            $table->integer(EXPERIENCE)->default(0);
            $table->float(PRICE);
            $table->char(CURRENCY, 3)->default('TRY');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     * @return void
     */
    public function down() {
        Schema::dropIfExists(DB_TUTOR_LECTURE_TABLE);
    }
}
