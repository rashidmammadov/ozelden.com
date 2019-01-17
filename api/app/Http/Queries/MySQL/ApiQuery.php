<?php

namespace App\Http\Queries\MySQL;

use App\SuitabilitySchedule;
use App\UserLecturesList;

class ApiQuery {

    public function __construct() {
        // 
    }

    /** -------- LECTURE QUERIES -------- **/

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

    /** -------- SUITABILITY SCHEDULE -------- **/

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
