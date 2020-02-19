<?php

namespace App\Http\Queries\MySQL;

use App\City;
use App\Lecture;
use Illuminate\Database\QueryException;

class DataQuery extends Query {

    /**
     * Get Turkey cities from DB.
     * @return mixed
     */
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

    /**
     * Get lectures from DB.
     * @return mixed
     */
    public static function getLectures() {
        try {
            $query = Lecture::get()
                ->groupBy(LECTURE_AREA);
            return $query;
        } catch (QueryException $e) {
            self::logException($e, debug_backtrace());
        }
    }

}
