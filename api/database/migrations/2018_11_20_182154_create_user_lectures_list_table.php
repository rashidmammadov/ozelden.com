<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateUserLecturesListTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up() {
        Schema::create('user_lectures_list', function (Blueprint $table) {
            $table->integer(USER_ID);
            $table->char(LECTURE_AREA, 99);
            $table->char(LECTURE_THEME, 99);
            $table->integer(EXPERIENCE);
            $table->float(PRICE);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     * @return void
     */
    public function down() {
        Schema::dropIfExists('user_lectures_list');
    }
}
