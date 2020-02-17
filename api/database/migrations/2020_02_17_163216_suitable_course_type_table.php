<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class SuitableCourseTypeTable extends Migration {
    /**
     * Run the migrations.
     * @return void
     */
    public function up() {
        Schema::create(DB_SUITABLE_COURSE_TYPE_TABLE, function (Blueprint $table) {
            $table->integer(TUTOR_ID)->unique()->references(IDENTIFIER)->on(DB_USERS_TABLE);
            $table->boolean(INDIVIDUAL);
            $table->boolean(GROUP);
            $table->boolean(CLASS_);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     * @return void
     */
    public function down() {
        Schema::dropIfExists(DB_SUITABLE_COURSE_TYPE_TABLE);
    }
}
