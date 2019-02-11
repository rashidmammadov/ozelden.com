<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateChildTable extends Migration
{
    /**
     * Run the migrations.
     * @return void
     */
    public function up()
    {
        Schema::create('child', function (Blueprint $table) {
            $table->increments(CHILD_ID);
            $table->char(TYPE, 10)->default(CHILD);
            $table->integer(USER_ID);
            $table->string(PICTURE, 99)->nullable();
            $table->char(NAME, 50);
            $table->char(SURNAME, 99);
            $table->char(SEX, 9);
            $table->bigInteger(BIRTH_DATE);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     * @return void
     */
    public function down() {
        Schema::dropIfExists('child');
    }
}
