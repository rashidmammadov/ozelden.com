<?php

use App\User;
use Illuminate\Database\QueryException;
use Illuminate\Support\Facades\Log;

class UserQuery {

    public static function checkEmail($email) {
        try {
            $query = User::where([
                [EMAIL, EQUAL_SIGN, $email]
            ])->exists();
            return $query;
        } catch (QueryException $e) {
            self::logException($e, debug_backtrace());
        }
    }

    /**
     * Save new user on users table with given parameters.
     * @param $user - holds the user data.
     * @return bool
     */
    public static function save($user) {
        try {
            User::create($user);
            return true;
        } catch (QueryException $e) {
            self::logException($e, debug_backtrace());
        }
    }

    /**
     * Log exception error.
     * @param QueryException $exception - holds the exception.
     * @param array $backtrace - holds the backtrace array.
     */
    private static function logException(QueryException $exception, $backtrace) {
        $caller = array_shift($backtrace);
        Log::error($exception->getMessage() . ' (' . $caller['class'] . '->' . $caller['function'] . ':' . $caller['line'] . ')');
    }

}
