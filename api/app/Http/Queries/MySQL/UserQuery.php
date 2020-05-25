<?php

namespace App\Http\Queries\MySQL;

use App\User;
use Illuminate\Database\QueryException;
use Illuminate\Support\Facades\Log;

class UserQuery extends Query {

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
     * find user with given id.
     * @param string $id - holds the id.
     * @return mixed
     */
    public static function getUserById(string $id) {
        try {
            $query = User::where([
                [IDENTIFIER, EQUAL_SIGN, $id]
            ])->first();
            return $query;
        } catch (QueryException $e) {
            self::logException($e, debug_backtrace());
        }
    }

    /**
     * Get users with type tutor.
     * @return mixed
     */
    public static function getTutors() {
        try {
            $query = User::where([
                [TYPE, EQUAL_SIGN, TUTOR]
            ])->get();
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
            $query = User::create([
                TYPE => $user[TYPE],
                NAME => $user[NAME],
                SURNAME => $user[SURNAME],
                BIRTHDAY => $user[BIRTHDAY],
                EMAIL => $user[EMAIL],
//                IDENTITY_NUMBER => $user[IDENTITY_NUMBER],
                PASSWORD => \Hash::make($user[PASSWORD]),
                SEX => $user[SEX],
                ONE_SIGNAL_DEVICE_ID => $user[ONE_SIGNAL_DEVICE_ID]
            ]);
            return $query;
        } catch (QueryException $e) {
            self::logException($e, debug_backtrace());
        }
    }

    /**
     * Update user`s data on db with given parameters.
     * @param $userId - holds the user id.
     * @param $user - holds the user data.
     * @return bool
     */
    public static function update($userId, $user) {
        try {
            User::where(IDENTIFIER, EQUAL_SIGN, $userId)
                ->update([
                    NAME => !empty($user[NAME]) ? $user[NAME] : null,
                    SURNAME => !empty($user[SURNAME]) ? $user[SURNAME] : null,
                    EMAIL => !empty($user[EMAIL]) ? $user[EMAIL] : null,
                    IDENTITY_NUMBER => !empty($user[IDENTITY_NUMBER]) ? $user[IDENTITY_NUMBER] : null,
                    SEX => !empty($user[SEX]) ? $user[SEX] : null,
                    BIRTHDAY => !empty($user[BIRTHDAY]) ? $user[BIRTHDAY] : null
                ]);
            return true;
        } catch (QueryException $e) {
            self::logException($e, debug_backtrace());
        }
    }

    /**
     * @description query to update user` password.
     * @param integer $email - the given user`s email
     * @param string $newPassword - updated password
     */
    public static function updateUserPassword($email, $newPassword) {
        User::where(EMAIL, EQUAL_SIGN, $email)
            ->update([PASSWORD => \Hash::make($newPassword)]);
    }

}
