<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class FinanceTable extends Migration {
    /**
     * Run the migrations.
     * @return void
     */
    public function up() {
        Schema::create(DB_FINANCE_TABLE, function (Blueprint $table) {
            $table->increments(FINANCE_ID);
            $table->integer(USER_ID)->references(IDENTIFIER)->on(DB_USERS_TABLE);
            $table->string(REFERENCE_CODE)->nullable();
            $table->string(ITEM)->nullable();
            $table->double(PRICE);
            $table->double(PRICE_WITH_COMMISSION);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     * @return void
     */
    public function down() {
        Schema::dropIfExists(DB_FINANCE_TABLE);
    }
}
