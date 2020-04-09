<?php

namespace App\Http\Queries\MySQL;

use App\Announcement;
use App\Http\Utilities\CustomDate;
use App\SuitableRegion;
use App\User;
use Illuminate\Database\QueryException;
use Illuminate\Support\Facades\Log;

class SearchQuery extends Query {

    /**
     * Search query in tutor table on DB with given parameters.
     * @param $params - holds the filter parameters.
     * @param $itemPerPage - holds the search result per page.
     * @return mixed
     */
    public static function searchTutor($params, $itemPerPage) {
        try {
            $orderColumn = RANKING_AVG;
            $orderType = 'desc';
            if ($params[ORDER]) {
                $exploded = explode(COMMA_SIGN, $params[ORDER]);
                $orderColumn = $exploded[0];
                $orderType = $exploded[1];
            }
            $query = SuitableRegion::where(function ($query) use ($params) {
                    $query->where(DB_SUITABLE_REGION_TABLE.'.'.CITY, EQUAL_SIGN, urldecode($params[CITY]));
                    if (!is_null($params[DISTRICT])) {
                        $query->where(DB_SUITABLE_REGION_TABLE.'.'.DISTRICT, EQUAL_SIGN, urldecode($params[DISTRICT]));
                    }
                })
                ->leftJoin(DB_TUTOR_LECTURE_TABLE, function ($join) use ($params) {
                    $join->on(DB_TUTOR_LECTURE_TABLE.'.'.TUTOR_ID, EQUAL_SIGN, DB_SUITABLE_REGION_TABLE.'.'.TUTOR_ID);
                })
                ->leftJoin(DB_AVERAGE_TABLE, function ($join) {
                    $join->on(DB_AVERAGE_TABLE.'.'.USER_ID, EQUAL_SIGN, DB_TUTOR_LECTURE_TABLE.'.'.TUTOR_ID);
                })
                ->leftJoin(DB_PAID_SERVICE_TABLE, function ($join) {
                    $join->on(DB_PAID_SERVICE_TABLE.'.'.TUTOR_ID, EQUAL_SIGN, DB_TUTOR_LECTURE_TABLE.'.'.TUTOR_ID)
                        ->where(DB_PAID_SERVICE_TABLE.'.'.BOOST, EQUAL_OR_GREATER_SIGN, CustomDate::currentMilliseconds());
                })
                ->where(function ($query) use ($params) {
                    $query->where(DB_TUTOR_LECTURE_TABLE.'.'.LECTURE_AREA, EQUAL_SIGN, urldecode($params[LECTURE_AREA]));
                    if (!is_null($params[LECTURE_THEME])) {
                        $query->where(DB_TUTOR_LECTURE_TABLE.'.'.LECTURE_THEME, EQUAL_SIGN, urldecode($params[LECTURE_THEME]));
                    }
                })
                ->orWhere(function ($query) use ($params) {
                    $query->where(DB_SUITABLE_REGION_TABLE.'.'.CITY, EQUAL_SIGN, urldecode($params[CITY]))
                        ->where(DB_SUITABLE_REGION_TABLE.'.'.DISTRICT, EQUAL_SIGN, 'Hepsi');
                })
                ->where(function ($query) use ($params) {
                    $query->where(DB_TUTOR_LECTURE_TABLE.'.'.LECTURE_AREA, EQUAL_SIGN, urldecode($params[LECTURE_AREA]))
                        ->where(DB_TUTOR_LECTURE_TABLE.'.'.LECTURE_THEME, EQUAL_SIGN, 'Tüm Konular');
                })
                ->orderBy(DB_PAID_SERVICE_TABLE.'.'.BOOST, 'desc')
                ->orderBy($orderColumn, $orderType)
                ->select(DB_SUITABLE_REGION_TABLE.'.'.TUTOR_ID, DB_PAID_SERVICE_TABLE.'.'.BOOST)
                ->distinct(DB_SUITABLE_REGION_TABLE.'.'.TUTOR_ID)
                ->paginate($itemPerPage);
            return $query;
        } catch (QueryException $e) {
            self::logException($e, debug_backtrace());
        }
    }

    /**
     * Search query in tutored table on DB with given parameters.
     * @param $params - holds the filter parameters.
     * @param $itemPerPage - holds the search result per page.
     * @return mixed
     */
    public static function searchTutored($params, $itemPerPage) {
        try {
            $query = Announcement::where(function ($query) use ($params) {
                    $query->where(DB_ANNOUNCEMENT_TABLE.'.'.CITY, EQUAL_SIGN, urldecode($params[CITY]));
                    $query->where(DB_ANNOUNCEMENT_TABLE.'.'.LECTURE_AREA, EQUAL_SIGN, urldecode($params[LECTURE_AREA]));
                    if (!is_null($params[DISTRICT])) {
                        $query->where(DB_ANNOUNCEMENT_TABLE.'.'.DISTRICT, EQUAL_SIGN, urldecode($params[DISTRICT]));
                    }
                    if (!is_null($params[LECTURE_THEME])) {
                        $query->where(DB_ANNOUNCEMENT_TABLE.'.'.LECTURE_THEME, EQUAL_SIGN, urldecode($params[LECTURE_THEME]));
                    }
                })
                ->leftJoin(DB_USERS_TABLE, function ($join) {
                    $join->on(DB_USERS_TABLE.'.'.IDENTIFIER, EQUAL_SIGN, DB_ANNOUNCEMENT_TABLE.'.'.TUTORED_ID);
                })
                ->leftJoin(DB_PROFILE_TABLE, function ($join) {
                    $join->on(DB_PROFILE_TABLE.'.'.USER_ID, EQUAL_SIGN, DB_USERS_TABLE.'.'.IDENTIFIER);
                })
                ->orWhere(function ($query) use ($params) {
                    $query->where(DB_ANNOUNCEMENT_TABLE.'.'.CITY, EQUAL_SIGN, urldecode($params[CITY]))
                        ->where(DB_ANNOUNCEMENT_TABLE.'.'.DISTRICT, EQUAL_SIGN, 'Hepsi');
                })
                ->where(function ($query) use ($params) {
                    $query->where(DB_ANNOUNCEMENT_TABLE.'.'.LECTURE_AREA, EQUAL_SIGN, urldecode($params[LECTURE_AREA]))
                        ->where(DB_ANNOUNCEMENT_TABLE.'.'.LECTURE_THEME, EQUAL_SIGN, 'Tüm Konular');
                })
                ->select('*', DB_ANNOUNCEMENT_TABLE.'.'.CITY,
                        DB_ANNOUNCEMENT_TABLE.'.'.DISTRICT,
                        DB_ANNOUNCEMENT_TABLE.'.'.UPDATED_AT)
                ->orderBy(DB_ANNOUNCEMENT_TABLE.'.'.UPDATED_AT, 'desc')
                ->paginate($itemPerPage);
            return $query;
        } catch (QueryException $e) {
            self::logException($e, debug_backtrace());
        }
    }

    /**
     * Get tutor connected tables from DB.
     * @param $tutorId - holds the tutor id.
     * @param $filters - holds the filters.
     * @return mixed
     */
    public static function getTutorConnections($tutorId, $filters = null) {
        try {
            $query = User::where(DB_USERS_TABLE.'.'.IDENTIFIER, EQUAL_SIGN, $tutorId)
                ->where(DB_USERS_TABLE.'.'.TYPE, EQUAL_SIGN, TUTOR)
                ->where(DB_USERS_TABLE.'.'.STATE, EQUAL_SIGN, USER_STATE_ACTIVE)
                ->where(function ($query) use ($filters) {
                    if ($filters && $filters[SEX]) {
                        $query->where(DB_USERS_TABLE.'.'.SEX, EQUAL_SIGN, $filters[SEX]);
                    }
                })
                ->leftJoin(DB_PROFILE_TABLE, function ($join) {
                    $join->on(DB_PROFILE_TABLE.'.'.USER_ID, EQUAL_SIGN, DB_USERS_TABLE.'.'.IDENTIFIER);
                })
                ->leftJoin(DB_AVERAGE_TABLE, function ($join) {
                    $join->on(DB_AVERAGE_TABLE.'.'.USER_ID, EQUAL_SIGN, DB_USERS_TABLE.'.'.IDENTIFIER);
                })
                ->leftJoin(DB_SUITABLE_REGION_TABLE, function ($join) {
                    $join->on(DB_SUITABLE_REGION_TABLE.'.'.TUTOR_ID, EQUAL_SIGN, DB_USERS_TABLE.'.'.IDENTIFIER);
                })
                ->leftJoin(DB_TUTOR_LECTURE_TABLE, function ($join) {
                    $join->on(DB_TUTOR_LECTURE_TABLE.'.'.TUTOR_ID, EQUAL_SIGN, DB_USERS_TABLE.'.'.IDENTIFIER);
                })
                ->where(function ($query) use ($filters) {
                    if ($filters && $filters[MIN_PRICE]) {
                        $query->where(DB_AVERAGE_TABLE.'.'.PRICE_AVG, EQUAL_OR_GREATER_SIGN, $filters[MIN_PRICE]);
                    }
                    if ($filters && $filters[MAX_PRICE]) {
                        $query->where(DB_AVERAGE_TABLE.'.'.PRICE_AVG, EQUAL_OR_LESS_SIGN, $filters[MAX_PRICE]);
                    }
                })
                ->get();
            return $query;
        } catch (QueryException $e) {
            self::logException($e, debug_backtrace());
        }
    }

    /**
     * Get recommended tutor`s from db is exists and bigger than current date.
     * @param $params = holds filters params.
     * @return mixed
     */
    public static function getRecommendedTutors($params) {
        try {
            $query = SuitableRegion::where(function ($query) use ($params) {
                    if (!is_null($params[CITY])) {
                        $query->where(DB_SUITABLE_REGION_TABLE . '.' . CITY, EQUAL_SIGN, urldecode($params[CITY]));
                    }
                })
                ->leftJoin(DB_PAID_SERVICE_TABLE, function ($join) {
                    $join->on(DB_PAID_SERVICE_TABLE.'.'.TUTOR_ID, EQUAL_SIGN, DB_SUITABLE_REGION_TABLE.'.'.TUTOR_ID)
                        ->where(DB_PAID_SERVICE_TABLE.'.'.RECOMMEND, EQUAL_OR_GREATER_SIGN, CustomDate::currentMilliseconds());
                })
                ->orderBy(DB_PAID_SERVICE_TABLE.'.'.RECOMMEND, 'desc')
                ->select(DB_SUITABLE_REGION_TABLE.'.'.TUTOR_ID, DB_PAID_SERVICE_TABLE.'.'.RECOMMEND)
                ->distinct(DB_SUITABLE_REGION_TABLE.'.'.TUTOR_ID)
                ->get();
            return $query;
        } catch (QueryException $e) {
            self::logException($e, debug_backtrace());
        }
    }
}
