<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class ProfileTable extends Migration {
    /**
     * Run the migrations.
     * @return void
     */
    public function up() {
        Schema::create(DB_PROFILE_TABLE, function (Blueprint $table) {
            $table->integer(USER_ID)->unique()->references(IDENTIFIER)->on(DB_USERS_TABLE);
            $table->string(PICTURE, 100)->nullable();
            $table->string(PHONE, 11)->nullable();
            $table->string(PROFESSION, 50)->nullable();
            $table->string(DESCRIPTION, 250)->nullable();
            $table->char(COUNTRY, 20)->nullable()->default(COUNTRY_TURKEY);
            $table->string(CITY, 20)->nullable();
            $table->string(DISTRICT, 30)->nullable();
            $table->string(ADDRESS, 200)->nullable();
            $table->char(LANGUAGE, 2)->nullable()->default(COUNTRY_TURKEY_CODE);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     * @return void
     */
    public function down() {
        Schema::dropIfExists(DB_PROFILE_TABLE);
    }
}
