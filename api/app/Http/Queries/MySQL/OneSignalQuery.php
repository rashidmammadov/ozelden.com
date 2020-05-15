<?php

namespace App\Http\Queries\MySQL;

use App\OneSignal;
use Illuminate\Database\QueryException;

class OneSignalQuery extends Query {

    /**
     * Set one signal id of user in DB.
     * @param $data - holds the data;
     * @return mixed
     */
    public static function create($data) {
        try {
            $query = OneSignal::create([
                USER_ID => $data[USER_ID],
                ONE_SIGNAL_DEVICE_ID => $data[ONE_SIGNAL_DEVICE_ID],
                DEVICE_TYPE => $data[DEVICE_TYPE],
                IP => $data[IP],
                STATUS => 1
            ]);
            return $query;
        } catch (QueryException $e) {
            self::logException($e, debug_backtrace());
        }
    }

}
