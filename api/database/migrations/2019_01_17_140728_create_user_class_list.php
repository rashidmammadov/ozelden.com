<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateUserClassList extends Migration
{
    /**
     * Run the migrations.
     * @return void
     */
    public function up() {
        Schema::create('user_class_list', function (Blueprint $table) {
            $table->increments(CLASS_ID);
            $table->integer(USER_ID);
            $table->char(CLASS_NAME, 30);
            $table->integer(TUTOR_ID);
            $table->char(LECTURE_AREA, 50);
            $table->char(LECTURE_THEME, 50);
            $table->char(CITY, 50);
            $table->char(DISTRICT, 50);
            $table->text(DAY, 500)->nullable();
            $table->text(CONTENT, 200)->nullable();
            $table->boolean(STATUS)->default(1);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     * @return void
     */
    public function down() {
        Schema::dropIfExists('user_class_list');
    }
}
