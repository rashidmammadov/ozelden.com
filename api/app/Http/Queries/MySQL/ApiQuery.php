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
        define('PROFILE', 'profile');
        define('USERS', 'users');
        define('USER_CLASS_LIST', 'user_class_list');
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
            ->join(USERS, (USERS.'.'.IDENTIFIER), EQUAL_SIGN, (USER_CLASS_LIST.'.'.TUTOR_ID))
            ->get(array(
                (USER_CLASS_LIST.'.'.STAR_SIGN),
                (USERS.'.'.IDENTIFIER), (USERS.'.'.NAME), (USERS.'.'.SURNAME)
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
    public function deleteUserSelectedLecture($userId, $parameters) {
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
    public function getUserLecturesList($userId) {
        $queryResult = UserLecturesList::where(USER_ID, $userId)->get();

        return $queryResult;
    }

    /**
     * @description query to get user`s selected lecture info
     * @param integer $userId
     * @param array $parameters
     * @return mixed query result
     */
    public function getUserSelectedLecture($userId, $parameters) {
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
    public function setUserLecture($userId, $parameters) {
        UserLecturesList::create([
            USER_ID => $userId,
            LECTURE_AREA => $parameters[LECTURE_AREA],
            LECTURE_THEME => $parameters[LECTURE_THEME],
            EXPERIENCE => $parameters[EXPERIENCE],
            PRICE => $parameters[PRICE]
        ]);
    }

    /* ------------------------- SUITABILITY SCHEDULE ------------------------- */

    /**
     * @description query to get user`s suitability schedule
     * @param integer $userId
     * @return mixed query result
     */
    public function getUserSuitabilitySchedule($userId) {
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
    public function setUserDefaultSuitabilitySchedule($userId, $region, $location, $courseType, $facility, $dayHourTable) {
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
     * @param string $region {array}
     * @param string $location {object}
     * @param string $courseType {object}
     * @param string $facility {object}
     * @param string $dayHourTable {object}
     */
    public function updateUserSuitabilitySchedule($userId, $region, $location, $courseType, $facility, $dayHourTable) {
        $queryResult = $this->getUserSuitabilitySchedule($userId);
        $queryResult->region = $region;
        $queryResult->location = $location;
        $queryResult->courseType = $courseType;
        $queryResult->facility = $facility;
        $queryResult->dayHourTable = $dayHourTable;
        $queryResult->save();
    }

}
