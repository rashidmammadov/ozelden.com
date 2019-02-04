<?php

namespace App\Http\Queries\MySQL;

use App\Profile;
use App\User;
use App\UserClassList;
use App\SuitabilitySchedule;
use App\UserLecturesList;
use Illuminate\Support\Facades\DB;

class ApiQuery {

    public function __construct() {
        // db table names
        define('PROFILE_TABLE', 'profile');
        define('USERS_TABLE', 'users');
        define('USER_CLASS_LIST_TABLE', 'user_class_list');
    }

    /* ------------------------- PROFILE QUERIES ------------------------- */

    /**
     * @description query to get user`s profile
     * @param integer $userId
     * @return mixed
     */
    public static function getUserProfile($userId) {
        $queryResult = Profile::where([
            [USER_ID, EQUAL_SIGN, $userId]
        ])->first();

        return $queryResult;
    }

    /**
     * @description query to set user`s default profile table
     * @param integer $userId
     */
    public static function setUserDefaultProfile($userId) {
        Profile::create([
            USER_ID => $userId
        ]);
    }

    /**
     * @description query to update user`s profile.
     * @param integer $userId
     * @param array $parameters
     */
    public static function updateUserProfile($userId, $parameters) {
        $queryResult = self::getUserProfile($userId);
        if (!empty($parameters[PICTURE])) {
            $queryResult->picture = $parameters[PICTURE];
        }
        if (!empty($parameters[PHONE])) {
            $queryResult->phone = $parameters[PHONE];
        }
        if (!empty($parameters[COUNTRY])) {
            $queryResult->country = $parameters[COUNTRY];
        }
        if (!empty($parameters[CITY])) {
            $queryResult->city = $parameters[CITY];
        }
        if (!empty($parameters[DISTRICT])) {
            $queryResult->district = $parameters[DISTRICT];
        }
        if (!empty($parameters[ADDRESS])) {
            $queryResult->address = $parameters[ADDRESS];
        }
        if (!empty($parameters[LANGUAGE])) {
            $queryResult->language = $parameters[LANGUAGE];
        }
        $queryResult->save();
    }

    /* ------------------------- USER CLASS QUERIES ------------------------- */

    /**
     * @description query to remove user`s class
     * @param integer $userId
     * @param integer $classId
     */
    public function deleteUserClass($userId, $classId) {
        UserClassList::where([
            [USER_ID, EQUAL_SIGN, $userId],
            [CLASS_ID, EQUAL_SIGN, $classId]
        ])->delete();
    }

    /**
     * @description query to get user`s class
     * @param $userId
     * @param $classId
     * @return mixed
     */
    public function getClass($userId, $classId) {
        $queryResult = UserClassList::where([
            [USER_ID, EQUAL_SIGN, $userId],
            [CLASS_ID, EQUAL_SIGN, $classId]
        ])->first();

        return $queryResult;
    }

    /**
     * @description query to get user`s class list
     * @param integer $userId
     * @return mixed query Result
     */
    public function getUserClassList($userId) {
        $queryResult = UserClassList::where(USER_ID, $userId)
            ->join(USERS_TABLE, (USERS_TABLE.'.'.IDENTIFIER), EQUAL_SIGN, (USER_CLASS_LIST_TABLE.'.'.TUTOR_ID))
            ->get(array(
                (USER_CLASS_LIST_TABLE.'.'.STAR_SIGN),
                (USERS_TABLE.'.'.IDENTIFIER), (USERS_TABLE.'.'.NAME), (USERS_TABLE.'.'.SURNAME)
            ));

        return $queryResult;
    }

    /**
     * @description query to set class to user`s class list
     * @param integer $userId
     * @param $parameters
     * @return mixed query Result
     */
    public function setUserClass($userId, $parameters) {
        UserClassList::create([
            USER_ID => $userId,
            CLASS_NAME => $parameters[CLASS_NAME],
            TUTOR_ID => $parameters[TUTOR_ID],
            LECTURE_AREA => $parameters[LECTURE_AREA],
            LECTURE_THEME => $parameters[LECTURE_THEME],
            CITY => $parameters[CITY],
            DISTRICT => $parameters[DISTRICT],
            DAY => $parameters[DAY],
            CONTENT => $parameters[CONTENT]
        ]);
    }

    /**
     * @description query to update class of user
     * @param integer $userId
     * @param integer $classId
     * @param array $parameters
     */
    public function updateUserClass($userId, $classId, $parameters) {
        $queryResult = $this->getClass($userId, $classId);
        $queryResult->className = $parameters[CLASS_NAME];
        $queryResult->tutorId = $parameters[TUTOR_ID];
        $queryResult->lectureArea = $parameters[LECTURE_AREA];
        $queryResult->lectureTheme = $parameters[LECTURE_THEME];
        $queryResult->city = $parameters[CITY];
        $queryResult->district = $parameters[DISTRICT];
        $queryResult->day = $parameters[DAY];
        $queryResult->content = $parameters[CONTENT];
        $queryResult->save();
    }

    /* ------------------------- USER LECTURE QUERIES ------------------------- */

    /**
     * @description query to delete given lecture from user`s lecture list
     * @param integer $userId
     * @param array $parameters
     */
    public static function deleteUserSelectedLecture($userId, $parameters) {
        UserLecturesList::where([
            [USER_ID, EQUAL_SIGN, $userId],
            [LECTURE_AREA, EQUAL_SIGN, $parameters[LECTURE_AREA]],
            [LECTURE_THEME, EQUAL_SIGN, $parameters[LECTURE_THEME]]
        ])->delete();
    }

    /**
     * @description query to get user`s lectures list
     * @param integer $userId
     * @return mixed query result
     */
    public static function getUserLecturesList($userId) {
        $queryResult = UserLecturesList::where(USER_ID, $userId)->get();

        return $queryResult;
    }

    /**
     * @description query to get user`s selected lecture info
     * @param integer $userId
     * @param array $parameters
     * @return mixed query result
     */
    public static function getUserSelectedLecture($userId, $parameters) {
        $queryResult = UserLecturesList::where([
            [USER_ID, EQUAL_SIGN, $userId],
            [LECTURE_AREA, EQUAL_SIGN, $parameters[LECTURE_AREA]],
            [LECTURE_THEME, EQUAL_SIGN, $parameters[LECTURE_THEME]]
        ])->first();

        return $queryResult;
    }

    /**
     * @description query to set given lecture to user`s lectures list
     * @param integer $userId
     * @param array $parameters
     */
    public static function setUserLecture($userId, $parameters) {
        UserLecturesList::create([
            USER_ID => $userId,
            LECTURE_AREA => $parameters[LECTURE_AREA],
            LECTURE_THEME => $parameters[LECTURE_THEME],
            EXPERIENCE => $parameters[EXPERIENCE],
            PRICE => $parameters[PRICE]
        ]);
    }

    /* ------------------------- SEARCH QUERIES ------------------------- */

    public static function searchTutor($type) {
        $lectureArea = 'PRIMARY_SCHOOL_REINFORCEMENT';
        $lectureTheme = null;
        $city = null;
        $district = null;
        $queryResult = User::where(TYPE, $type)
            ->join('profile', ('users'.'.'.IDENTIFIER), EQUAL_SIGN, ('profile'.'.'.USER_ID))
            ->join('user_lectures_list', ('users'.'.'.IDENTIFIER), EQUAL_SIGN, ('user_lectures_list'.'.'.USER_ID))
            ->join('user_suitability_schedule', ('users'.'.'.IDENTIFIER), EQUAL_SIGN, ('user_suitability_schedule'.'.'.USER_ID))
            ->where('user_lectures_list'.'.'.LECTURE_AREA, EQUAL_SIGN, $lectureArea)
            ->where('user_lectures_list'.'.'.LECTURE_THEME, 'LIKE', $lectureTheme) // must be equal fully
            ->where('user_suitability_schedule'.'.'.REGION, 'LIKE', '%'.$city.'%')
            ->where('user_suitability_schedule'.'.'.REGION, 'LIKE', '%'.$district.'%')
            ->get();

        return $queryResult;
    }

    /* ------------------------- SUITABILITY SCHEDULE ------------------------- */

    /**
     * @description query to get user`s suitability schedule
     * @param integer $userId
     * @return mixed query result
     */
    public static function getUserSuitabilitySchedule($userId) {
        $queryResult = SuitabilitySchedule::where(USER_ID, $userId)->first();

        return $queryResult;
    }

    /**
     * @description query to set user`s suitability schedule
     * @param integer $userId
     * @param string $region {array}
     * @param string $location {object}
     * @param string $courseType {object}
     * @param string $facility {object}
     * @param string $dayHourTable {object}
     */
    public static function setUserDefaultSuitabilitySchedule($userId, $region, $location, $courseType, $facility, $dayHourTable) {
        SuitabilitySchedule::create([
            USER_ID => $userId,
            REGION => $region,
            LOCATION => $location,
            COURSE_TYPE => $courseType,
            FACILITY => $facility,
            DAY_HOUR_TABLE => $dayHourTable
        ]);
    }

    /**
     * @description query to update user`s suitability schedule
     * @param integer $userId
     * @param $parameters
     */
    public static function updateUserSuitabilitySchedule($userId, $parameters) {
        $queryResult = self::getUserSuitabilitySchedule($userId);
        if (!empty($parameters[REGION])) {
            $queryResult->region = json_encode($parameters[REGION]);
        }
        if (!empty($parameters[LOCATION])) {
            $queryResult->location = json_encode($parameters[LOCATION]);
        }
        if (!empty($parameters[COURSE_TYPE])) {
            $queryResult->courseType = json_encode($parameters[COURSE_TYPE]);
        }
        if (!empty($parameters[FACILITY])) {
            $queryResult->facility = json_encode($parameters[FACILITY]);
        }
        if (!empty($parameters[DAY_HOUR_TABLE])) {
            $queryResult->dayHourTable = json_encode($parameters[DAY_HOUR_TABLE]);
        }
        $queryResult->save();
    }

}
