<?php


namespace App\Http\Queries\MySQL;

use Illuminate\Database\QueryException;
use Illuminate\Support\Facades\Log;

class Query {

    /**
     * Log exception error.
     * @param QueryException $exception - holds the exception.
     * @param array $backtrace - holds the backtrace array.
     */
    public static function logException(QueryException $exception, $backtrace) {
        $caller = array_shift($backtrace);
        Log::error($exception->getMessage() . ' (' . $caller['class'] . '->' . $caller['function'] . ':' . $caller['line'] . ')');
    }

}
