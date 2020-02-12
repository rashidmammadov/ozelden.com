<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class City extends Model {

    protected $table = DB_CITY_TABLE;
    protected $primaryKey = CITY_ID;

    protected $fillable = [
        CITY_ID, COUNTRY_CODE, CITY_NAME, DISTRICT_NAME
    ];
}
