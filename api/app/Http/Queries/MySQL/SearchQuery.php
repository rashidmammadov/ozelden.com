<?php

namespace App\Http\Queries\MySQL;

use App\SuitableRegion;
use App\User;
use Illuminate\Database\QueryException;

class SearchQuery extends Query {

    /**
     * Search query in tutor table on DB with given parameters.
     * @param $params - holds the filter parameters.
     * @param $itemPerPage - holds the search result per page.
     * @return mixed
     */
    public static function searchTutor($params, $itemPerPage) {
        try {
            $query = SuitableRegion::where(function ($query) use ($params) {
                    $query->where(DB_SUITABLE_REGION_TABLE.'.'.CITY, EQUAL_SIGN, $params[CITY]);
                    if (!is_null($params[DISTRICT])) {
                        $query->where(DB_SUITABLE_REGION_TABLE.'.'.DISTRICT, EQUAL_SIGN, $params[DISTRICT]);
                    }
                })
                ->leftJoin(DB_TUTOR_LECTURE_TABLE, function ($join) use ($params) {
                    $join->on(DB_TUTOR_LECTURE_TABLE.'.'.TUTOR_ID, EQUAL_SIGN, DB_SUITABLE_REGION_TABLE.'.'.TUTOR_ID);
                })
                ->where(function ($query) use ($params) {
                    $query->where(DB_TUTOR_LECTURE_TABLE.'.'.LECTURE_AREA, EQUAL_SIGN, $params[LECTURE_AREA]);
                    if (!is_null($params[LECTURE_THEME])) {
                        $query->where(DB_TUTOR_LECTURE_TABLE.'.'.LECTURE_THEME, EQUAL_SIGN, $params[LECTURE_THEME]);
                    }
                })
                ->select(DB_SUITABLE_REGION_TABLE.'.'.TUTOR_ID)
                ->distinct()
                ->paginate($itemPerPage);
            return $query;
        } catch (QueryException $e) {
            self::logException($e, debug_backtrace());
        }
    }

    /**
     * Get tutor connected tables from DB.
     * @param $tutorId - holds the tutor id.
     * @return mixed
     */
    public static function getTutorConnections($tutorId) {
        try {
            $query = User::where(DB_USERS_TABLE.'.'.IDENTIFIER, EQUAL_SIGN, $tutorId)
                ->leftJoin(DB_PROFILE_TABLE, function ($join) {
                    $join->on(DB_PROFILE_TABLE.'.'.USER_ID, EQUAL_SIGN, DB_USERS_TABLE.'.'.IDENTIFIER);
                })
                ->leftJoin(DB_SUITABLE_REGION_TABLE, function ($join) {
                    $join->on(DB_SUITABLE_REGION_TABLE.'.'.TUTOR_ID, EQUAL_SIGN, DB_USERS_TABLE.'.'.IDENTIFIER);
                })
                ->leftJoin(DB_TUTOR_LECTURE_TABLE, function ($join) {
                    $join->on(DB_TUTOR_LECTURE_TABLE.'.'.TUTOR_ID, EQUAL_SIGN, DB_USERS_TABLE.'.'.IDENTIFIER);
                })
                ->get();
            return $query;
        } catch (QueryException $e) {
            self::logException($e, debug_backtrace());
        }
    }
}
