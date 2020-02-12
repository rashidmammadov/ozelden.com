<?php

namespace App\Http\Queries\MySQL;

use App\City;
use Illuminate\Database\QueryException;

class DataQuery extends Query {

    public static function getCities() {
        try {
            $query = City::where(COUNTRY_CODE, EQUAL_SIGN, COUNTRY_TURKEY_CODE)
                ->get()
                ->groupBy(CITY_NAME);
            return $query;
        } catch (QueryException $e) {
            self::logException($e, debug_backtrace());
        }
    }

}
