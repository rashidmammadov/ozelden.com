<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class StudentTable extends Migration {
    /**
     * Run the migrations.
     * @return void
     */
    public function up() {
        Schema::create(DB_STUDENT_TABLE, function (Blueprint $table) {
            $table->increments(STUDENT_ID);
            $table->char(TYPE, 10)->default(STUDENT);
            $table->integer(PARENT_ID)->references(IDENTIFIER)->on(DB_USERS_TABLE);
            $table->string(PICTURE, 100)->nullable();
            $table->char(NAME, 100);
            $table->char(SURNAME, 100);
            $table->char(BIRTHDAY, 15);
            $table->char(SEX, 10);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     * @return void
     */
    public function down() {
        Schema::dropIfExists(DB_STUDENT_TABLE);
    }
}
