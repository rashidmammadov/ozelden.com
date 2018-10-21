<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateUserSuitabilityScheduleTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('user_suitability_schedule', function (Blueprint $table) {
            $table->integer('userId');
            $table->string('region')->nullable();
            $table->string('location')->nullable();
            $table->string('courseType')->nullable();
            $table->string('facility')->nullable();
            $table->string('dayHourTable')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('user_suitability_schedule');
    }
}
