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
            $table->integer('userId');
            $table->char('lectureArea', 99);
            $table->char('lectureTheme', 99);
            $table->integer('experience');
            $table->float('price');
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
