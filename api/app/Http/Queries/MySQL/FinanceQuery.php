<?php

namespace App\Http\Queries\MySQL;

use App\Finance;
use Illuminate\Database\QueryException;

class FinanceQuery extends Query {

    /**
     * Save finance on DB.
     * @param $finance - holds the finance detail.
     * @return mixed
     */
    public static function save($finance) {
        try {
            $query = Finance::create([
                USER_ID => $finance[USER_ID],
                REFERENCE_CODE => $finance[REFERENCE_CODE],
                ITEM => $finance[ITEM],
                PRICE => $finance[PRICE],
                PRICE_WITH_COMMISSION => $finance[PRICE_WITH_COMMISSION]
            ]);
            return $query;
        } catch (QueryException $e) {
            self::logException($e, debug_backtrace());
        }
    }

}
