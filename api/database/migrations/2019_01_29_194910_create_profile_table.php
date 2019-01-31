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
            $table->integer(USER_ID);
            $table->string(PICTURE, 99)->nullable();
            $table->string(PHONE, 11)->nullable();
            $table->char(COUNTRY, 2)->nullable()->default(DOMAIN_TURKEY);
            $table->string(CITY, 20)->nullable();
            $table->string(DISTRICT, 20)->nullable();
            $table->text(ADDRESS, 200)->nullable();
            $table->char(LANGUAGE, 2)->nullable()->default(DOMAIN_TURKEY);
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
