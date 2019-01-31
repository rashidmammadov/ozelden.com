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
            $table->integer(USER_ID);
            $table->string(REGION)->nullable();
            $table->string(LOCATION)->nullable();
            $table->string(COURSE_TYPE)->nullable();
            $table->string(FACILITY)->nullable();
            $table->text(DAY_HOUR_TABLE, 500)->nullable();
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
