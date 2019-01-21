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
            $table->increments('classId');
            $table->integer('userId');
            $table->char('className', 30);
            $table->integer('tutorId');
            $table->char('lectureArea', 50);
            $table->char('lectureTheme', 50);
            $table->char('city', 50);
            $table->char('district', 50);
            $table->text('day', 500)->nullable();
            $table->text('content', 200)->nullable();
            $table->boolean('status')->default(1);
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
