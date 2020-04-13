<?php

namespace App\Http\Queries\MySQL;

use App\Http\Utilities\CustomDate;
use App\Profile;
use App\User;
use Illuminate\Database\QueryException;
use Illuminate\Support\Facades\Log;

class ProfileQuery extends Query {

    /**
     * Check if given user`s has picture.
     * @param $userId - holds the user id.
     * @return mixed
     */
    public static function checkPicture($userId) {
        try {
            $query = Profile::where(USER_ID, EQUAL_SIGN, $userId)
                ->where(PICTURE, NOT_EQUAL_SIGN, null)
                ->exists();
            return $query;
        } catch (QueryException $e) {
            self::logException($e, debug_backtrace());
        }
    }

    /**
     * Get user`s profile with connected tables from DB.
     * @param $userId - holds the user id.
     * @return mixed
     */
    public static function getProfileConnections($userId) {
        try {
            $query = User::where(DB_USERS_TABLE.'.'.IDENTIFIER, EQUAL_SIGN, $userId)
                ->where(DB_USERS_TABLE.'.'.STATE, EQUAL_SIGN, USER_STATE_ACTIVE)
                ->leftJoin(DB_PROFILE_TABLE, function ($join) {
                    $join->on(DB_PROFILE_TABLE.'.'.USER_ID, EQUAL_SIGN, DB_USERS_TABLE.'.'.IDENTIFIER);
                })
                ->leftJoin(DB_AVERAGE_TABLE, function ($join) {
                    $join->on(DB_AVERAGE_TABLE.'.'.USER_ID, EQUAL_SIGN, DB_USERS_TABLE.'.'.IDENTIFIER);
                })
                ->leftJoin(DB_PAID_SERVICE_TABLE, function ($join) {
                    $join->on(DB_PAID_SERVICE_TABLE.'.'.TUTOR_ID, EQUAL_SIGN, DB_USERS_TABLE.'.'.IDENTIFIER);
                })
                ->select('*', DB_USERS_TABLE.'.'.CREATED_AT)
                ->first();
            return $query;
        } catch (QueryException $e) {
            self::logException($e, debug_backtrace());
        }
    }

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
     * @param $profile - holds the profile data.
     * @return mixed
     */
    public static function saveDefault($userId, $profile) {
        try {
            $query = Profile::create([
                USER_ID => $userId,
                PHONE => !empty($profile[PHONE]) ? $profile[PHONE] : null,
                PROFESSION => !empty($profile[PROFESSION]) ? $profile[PROFESSION] : null,
                DESCRIPTION => !empty($profile[DESCRIPTION]) ? $profile[DESCRIPTION] : null,
                COUNTRY => !empty($profile[COUNTRY]) ? $profile[COUNTRY] : COUNTRY_TURKEY,
                CITY => !empty($profile[CITY]) ? $profile[CITY] : null,
                DISTRICT => !empty($profile[DISTRICT]) ? $profile[DISTRICT] : null,
                ADDRESS => !empty($profile[ADDRESS]) ? $profile[ADDRESS] : null,
                LANGUAGE => !empty($profile[LANGUAGE]) ? $profile[LANGUAGE] : COUNTRY_TURKEY_CODE
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
                    PROFESSION => !empty($profile[PROFESSION]) ? $profile[PROFESSION] : null,
                    DESCRIPTION => !empty($profile[DESCRIPTION]) ? $profile[DESCRIPTION] : null,
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
