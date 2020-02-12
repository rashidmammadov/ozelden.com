<?php

namespace App\Http\Queries\MySQL;

use App\User;
use Illuminate\Database\QueryException;
use Illuminate\Support\Facades\Log;

class UserQuery {

    /**
     * Check email if exist on db.
     * @param string $email - holds the checked email.
     * @return mixed
     */
    public static function checkEmail(string $email) {
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
     * find user with given email address.
     * @param string $email - holds the email.
     * @return mixed
     */
    public static function getUserByEmail(string $email) {
        try {
            $query = User::where([
                [EMAIL, EQUAL_SIGN, $email]
            ])->first();
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
            User::create([
                TYPE => $user[TYPE],
                NAME => $user[NAME],
                SURNAME => $user[SURNAME],
                BIRTHDAY => $user[BIRTHDAY],
                EMAIL => $user[EMAIL],
                PASSWORD => \Hash::make($user[PASSWORD]),
                SEX => $user[SEX],
                ONESIGNAL_DEVICE_ID => $user[ONESIGNAL_DEVICE_ID]
            ]);
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
