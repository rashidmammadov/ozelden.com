<?php

namespace App\Http\Controllers;

use App\Http\Models\ReportQuery;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use JWTAuth;
use Tymon\JWTAuth\Exceptions\JWTException;
use Validator;

class ReportController extends ApiController {

    /**
     * Handle request to return reports.
     * @param Request $request
     * @param $type - holds the report type and it must be null
     * @return mixed
     */
    public function get(Request $request, $type = null) {
        try {
            $user = JWTAuth::parseToken()->authenticate();
            if (is_null($type)) {
                $query = ReportQuery::filterTutorsWithParameters($request);
                if ($query) {
                    $totalCount = $this->getTotalCount($query);
                    $averagePrice = $this->getAveragePrice($query);
                    $genderDistribution = $this->getGenderDistribution($query);
                    $priceDistribution = $this->getPriceDistribution($query);
                    $result = array(
                        TOTAL_COUNT => $totalCount,
                        AVERAGE_PRICE => number_format($averagePrice, 2, '.', ''),
                        GENDER_DISTRIBUTION => $genderDistribution,
                        PRICE_DISTRIBUTION => $priceDistribution
                    );
                    return $this->respondCreated('', $result);
                }
            }
        } catch (JWTException $e) {
            $this->setStatusCode(401);
            $this->setMessage(AUTHENTICATION_ERROR);
            return $this->respondWithError($e->getMessage());
        }
    }

    /**
     * Handle request to get top 3 cities and lectures.
     * @return mixed
     */
    public function overview() {
        $topThreeCities = ReportQuery::getTopThreeCities();
        $topThreeLectures = ReportQuery::getTopThreeLectures();
        $data = array(
            TOP_CITIES => $topThreeCities,
            TOP_LECTURES => $topThreeLectures
        );
        return $this->respondCreated('', $data);
    }

    private function getAveragePrice($query) {
        return $query->select(DB_SUITABLE_REGION_TABLE.'.'.TUTOR_ID, DB_TUTOR_LECTURE_TABLE.'.'.PRICE)
            ->distinct(DB_SUITABLE_REGION_TABLE.'.'.TUTOR_ID)
            ->pluck(DB_TUTOR_LECTURE_TABLE.'.'.PRICE)
            ->avg();
    }

    private function getGenderDistribution($query) {
        return $query->leftJoin(DB_USERS_TABLE, function ($join) {
                $join->on(DB_USERS_TABLE.'.'.IDENTIFIER, EQUAL_SIGN, DB_SUITABLE_REGION_TABLE.'.'.TUTOR_ID)
                    ->where(DB_USERS_TABLE.'.'.STATE, EQUAL_SIGN, USER_STATE_ACTIVE);
            })
            ->select(DB_SUITABLE_REGION_TABLE.'.'.TUTOR_ID, DB_USERS_TABLE.'.'.SEX)
            ->distinct(DB_SUITABLE_REGION_TABLE.'.'.TUTOR_ID)
            ->get()
            ->mapToGroups(function ($user) {
                return array($user[SEX] => $user);
            })
            ->mapToGroups(function ($map) {
                $key = $map[0][SEX];
                $value = count($map);
                return array(array(KEY => $key, VALUE => $value));
            })
            ->first();
    }

    private function getPriceDistribution($query) {
        $ranges = array(
            '<50' => array(0, 50),
            '50-100' => array(50, 100),
            '100-150' => array(100, 150),
            '150-200' => array(150, 200),
            '>200' => array(200, INF)
        );
        $groups = array(
            array(KEY => '<50', VALUE => 0),
            array(KEY => '50-100', VALUE => 0),
            array(KEY => '100-150', VALUE => 0),
            array(KEY => '150-200', VALUE => 0),
            array(KEY => '>200', VALUE => 0),
        );
        $result = $query->select(DB_SUITABLE_REGION_TABLE.'.'.TUTOR_ID, DB_TUTOR_LECTURE_TABLE.'.'.PRICE)
            ->distinct(DB_SUITABLE_REGION_TABLE.'.'.TUTOR_ID)
            ->get()
            ->map(function ($lecture) use ($ranges) {
                $price = $lecture[PRICE];
                foreach($ranges as $key => $interval)  {
                    if ($interval[0] <= $price && $price < $interval[1]) {
                        $lecture[RANGE] = $key;
                        break;
                    }
                }
                return $lecture;
            })
            ->mapToGroups(function ($lecture) {
                return [$lecture[RANGE] => $lecture];
            })
            ->map(function ($map) {
                return count($map);
            });

        foreach ($groups as $index => $group) {
            $key = $group[KEY];
            if (!empty($result[$key])) {
                $groups[$index][VALUE] = $result[$group[KEY]];
            }
        }
        return $groups;
    }

    private function getTotalCount($query) {
        return $query->select(DB_SUITABLE_REGION_TABLE.'.'.TUTOR_ID)
            ->distinct(DB_SUITABLE_REGION_TABLE.'.'.TUTOR_ID)
            ->count();
    }

}
