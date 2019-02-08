<?php

namespace App\Http\Queries\MySQL;

use App\Profile;
use App\User;
use App\UserClassList;
use App\SuitabilitySchedule;
use App\UserLecturesList;
use Illuminate\Support\Facades\DB;

class ApiQuery {

    const profile = 'profile';
    const users = 'users';
    const userClassList = 'user_class_list';
    const userLecturesList = 'user_lectures_list';
    const userSuitabilitySchedule = 'user_suitability_schedule';

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
            ->join(self::users, (self::users.'.'.IDENTIFIER), EQUAL_SIGN, (self::userClassList.'.'.TUTOR_ID))
            ->get(array(
                (self::userClassList.'.'.STAR_SIGN),
                (self::users.'.'.IDENTIFIER), (self::users.'.'.NAME), (self::users.'.'.SURNAME)
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

    /**
     * @description query to get tutor`s search result.
     * @param string $lectureArea
     * @param string $lectureTheme
     * @param string $city
     * @param string $district
     * @param integer $maxPrice
     * @return mixed query result
     */
    public static function searchTutor($lectureArea, $lectureTheme, $city, $district, $maxPrice) {
        $queryResult = User::where(TYPE, TUTOR)
            ->join(self::profile, (self::users.'.'.IDENTIFIER), EQUAL_SIGN, (self::profile.'.'.USER_ID))
            ->join(self::userLecturesList, (self::users.'.'.IDENTIFIER), EQUAL_SIGN, (self::userLecturesList.'.'.USER_ID))
            ->join(self::userSuitabilitySchedule, (self::users.'.'.IDENTIFIER), EQUAL_SIGN, (self::userSuitabilitySchedule.'.'.USER_ID))

            ->where(self::userLecturesList.'.'.LECTURE_AREA, EQUAL_SIGN, $lectureArea)
            /** the lecture theme can be search by null or user can give all themes. **/
            ->where(function ($query) use ($lectureTheme) {
                $query->where(self::userLecturesList.'.'.LECTURE_THEME, LIKE_SIGN, $lectureTheme)
                    ->orWhere(self::userLecturesList.'.'.LECTURE_THEME, LIKE_SIGN, 'ALL');
            })
            /** lectures should be on given range **/
            ->where(function ($query) use ($maxPrice) {
                if ($maxPrice) {
                    $query->where(self::userLecturesList.'.'.PRICE, LESS_OR_EQUAL_SIGN, $maxPrice);
                }
            })
            /** the district can be search by null or user can contains all districts of selected city. **/
            ->where(function ($query) use ($city, $district) {
                $query->where(self::userSuitabilitySchedule.'.'.REGION, LIKE_SIGN,
                        '%city":"'.$city.'","district":"'.$district.'%')
                    ->orWhere(self::userSuitabilitySchedule.'.'.REGION, LIKE_SIGN,
                        '%city":"'.$city.'","district":"hepsi%');
            })
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
            $queryResult->region = json_encode($parameters[REGION], JSON_UNESCAPED_UNICODE);
        }
        if (!empty($parameters[LOCATION])) {
            $queryResult->location = json_encode($parameters[LOCATION], JSON_UNESCAPED_UNICODE);
        }
        if (!empty($parameters[COURSE_TYPE])) {
            $queryResult->courseType = json_encode($parameters[COURSE_TYPE], JSON_UNESCAPED_UNICODE);
        }
        if (!empty($parameters[FACILITY])) {
            $queryResult->facility = json_encode($parameters[FACILITY], JSON_UNESCAPED_UNICODE);
        }
        if (!empty($parameters[DAY_HOUR_TABLE])) {
            $queryResult->dayHourTable = json_encode($parameters[DAY_HOUR_TABLE], JSON_UNESCAPED_UNICODE);
        }
        $queryResult->save();
    }

}
