<?php

namespace App\Http\Queries\MySQL;

use App\Profile;
use Illuminate\Database\QueryException;

class ProfileQuery extends Query {

    /**
     * Get profile detail of user by given user`s id.
     * @param $userId - holds the user id.
     * @return mixed
     */
    public static function getProfileById($userId) {
        try {
            $query = Profile::where(
                USER_ID, EQUAL_SIGN, $userId
            )->first();
            return $query;
        } catch (QueryException $e) {
            self::logException($e, debug_backtrace());
        }
    }

    /**
     * Save profile on db with given user id to set default values.
     * @param $userId - holds the user id.
     * @return mixed
     */
    public static function saveDefault($userId) {
        try {
            $query = Profile::create([
                USER_ID => $userId
            ]);
            return $query;
        } catch (QueryException $e) {
            self::logException($e, debug_backtrace());
        }
    }

    /**
     * Update profile of user with given parameters.
     * @param $userId - holds the user id.
     * @param $profile - holds the profile data.
     * @return bool
     */
    public static function update($userId, $profile) {
        try {
            Profile::where(USER_ID, EQUAL_SIGN, $userId)
                ->update([
                    PHONE => !empty($profile[PHONE]) ? $profile[PHONE] : null,
                    COUNTRY => !empty($profile[COUNTRY]) ? $profile[COUNTRY] : COUNTRY_TURKEY,
                    CITY => !empty($profile[CITY]) ? $profile[CITY] : null,
                    DISTRICT => !empty($profile[DISTRICT]) ? $profile[DISTRICT] : null,
                    ADDRESS => !empty($profile[ADDRESS]) ? $profile[ADDRESS] : null,
                    LANGUAGE => !empty($profile[LANGUAGE]) ? $profile[LANGUAGE] : COUNTRY_TURKEY_CODE
                ]);
            return true;
        } catch (QueryException $e) {
            self::logException($e, debug_backtrace());
        }
    }

    /**
     * Update profile picture of user.
     * @param $userId - holds the user id.
     * @param $picture - holds the uploaded picture name.
     * @return bool
     */
    public static function updatePicture($userId, $picture) {
        try {
            Profile::where(USER_ID, EQUAL_SIGN, $userId)
                ->update([PICTURE => $picture]);
            return true;
        } catch (QueryException $e) {
            self::logException($e, debug_backtrace());
        }
    }
}
