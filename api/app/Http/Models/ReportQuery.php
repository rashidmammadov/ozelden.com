<?php

namespace App\Http\Models;

use App\Http\Queries\MySQL\Query;
use App\SuitableRegion;
use App\TutorLecture;
use Illuminate\Database\QueryException;
use Illuminate\Support\Facades\DB;

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

    /**
     * Handle query to get top 3 cities.
     * @return mixed
     */
    public static function getTopThreeCities() {
        try {
            $query = SuitableRegion::select(CITY, DB::raw('count(*) as ' . TOTAL))
                ->groupBy(CITY)
                ->orderBy(TOTAL, 'DESC')
                ->skip(0)->take(3)
                ->get();
            return $query;
        } catch (QueryException $e) {
            self::logException($e, debug_backtrace());
        }
    }

    /**
     * Handle query to get top 3 lectures.
     * @return mixed
     */
    public static function getTopThreeLectures() {
        try {
            $query = TutorLecture::where(LECTURE_THEME, NOT_EQUAL_SIGN, 'Hepsi')
                ->select(LECTURE_THEME, DB::raw('count(*) as ' . TOTAL))
                ->groupBy(LECTURE_THEME)
                ->orderBy(TOTAL, 'DESC')
                ->skip(0)->take(3)
                ->get();
            return $query;
        } catch (QueryException $e) {
            self::logException($e, debug_backtrace());
        }
    }
}
