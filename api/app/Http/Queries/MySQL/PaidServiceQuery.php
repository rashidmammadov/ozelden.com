<?php

namespace App\Http\Queries\MySQL;

use App\PaidService;
use Illuminate\Database\QueryException;

class PaidServiceQuery extends Query {

    /**
     * Get tutor`s paid service from DB.
     * @param $tutorId - holds the tutor id.
     * @return mixed
     */
    public static function get($tutorId) {
        try {
            $query = PaidService::where(TUTOR_ID, EQUAL_SIGN, $tutorId)
                ->first();
            return $query;
        } catch (QueryException $e) {
            self::logException($e, debug_backtrace());
        }
    }

    /**
     * Update paid services of tutor on DB.
     * @param $tutorId - holds the tutor id.
     * @param $paidService - holds the paid services.
     * @return bool
     */
    public static function update($tutorId, $paidService) {
        try {
            PaidService::updateOrCreate(
                [TUTOR_ID => $tutorId],
                [BID => $paidService[BID], BOOST => $paidService[BOOST], RECOMMEND => $paidService[RECOMMEND]]
            );
            return true;
        } catch (QueryException $e) {
            self::logException($e, debug_backtrace());
        }
    }

}
