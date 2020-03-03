<?php

namespace App\Http\Queries\MySQL;

use App\Average;
use Illuminate\Database\QueryException;

class AverageQuery extends Query {

    /**
     * Update average of user on DB.
     * @param $userId - holds the user id.
     * @param $average - holds the average data.
     * @return bool
     */
    public static function update($userId, $average) {
        try {
            Average::updateOrCreate(
                [USER_ID => $userId],
                [RANKING_AVG => $average[RANKING_AVG], EXPERIENCE_AVG => $average[EXPERIENCE_AVG], PRICE_AVG => $average[PRICE_AVG]]
            );
            return true;
        } catch (QueryException $e) {
            self::logException($e, debug_backtrace());
        }
    }

}
