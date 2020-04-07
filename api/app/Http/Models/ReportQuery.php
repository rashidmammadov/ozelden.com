<?php

namespace App\Http\Models;

use App\Http\Queries\MySQL\Query;
use App\SuitableRegion;
use Illuminate\Database\QueryException;

class ReportQuery extends Query {

    /**
     * Handle query to filter tutors with given region and lecture parameters.
     * @param $params - holds the parameters.
     * @return mixed
     */
    public static function filterTutorsWithParameters($params = null) {
        try {
            $query = SuitableRegion::where(function ($query) use ($params) {
                    if (!is_null($params) && !is_null($params[CITY])) {
                        $query->where(DB_SUITABLE_REGION_TABLE.'.'.CITY, EQUAL_SIGN, urldecode($params[CITY]));
                    }
                    if (!is_null($params) && !is_null($params[DISTRICT])) {
                        $query->where(DB_SUITABLE_REGION_TABLE.'.'.DISTRICT, EQUAL_SIGN, urldecode($params[DISTRICT]));
                    }
                })
                ->leftJoin(DB_TUTOR_LECTURE_TABLE, function ($join) use ($params) {
                    $join->on(DB_TUTOR_LECTURE_TABLE.'.'.TUTOR_ID, EQUAL_SIGN, DB_SUITABLE_REGION_TABLE.'.'.TUTOR_ID);
                })
                ->where(function ($query) use ($params) {
                    if (!is_null($params) && !is_null($params[LECTURE_AREA])) {
                        $query->where(DB_TUTOR_LECTURE_TABLE.'.'.LECTURE_AREA, EQUAL_SIGN, urldecode($params[LECTURE_AREA]));
                    }
                    if (!is_null($params) && !is_null($params[LECTURE_THEME])) {
                        $query->where(DB_TUTOR_LECTURE_TABLE.'.'.LECTURE_THEME, EQUAL_SIGN, urldecode($params[LECTURE_THEME]));
                    }
                });
            return $query;
        } catch (QueryException $e) {
            self::logException($e, debug_backtrace());
        }
    }
}
