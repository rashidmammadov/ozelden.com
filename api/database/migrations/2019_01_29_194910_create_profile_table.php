<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateProfileTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('profile', function (Blueprint $table) {
            $table->integer('userId');
            $table->string('picture', 99)->nullable();
            $table->string('phone', 11)->nullable();
            $table->char('country', 2)->nullable();
            $table->string('city', 20)->nullable();
            $table->string('district', 20)->nullable();
            $table->text('address', 200)->nullable();
            $table->char('language', 2)->nullable();
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
        Schema::dropIfExists('profile');
    }
}
