<?php

namespace App\Http\Queries\MySQL;

use App\UserLecturesList;

class ApiQuery {

    public function __construct() {
        // 
    }

    /* -------- LECTURE QUERIES -------- */

    /**
     * @description query to delete given lecture from user`s lecture list
     * @param $userId
     * @param $parameters
     */
    public function deleteUserSelectedLecture($userId, $parameters) {
        UserLecturesList::where([
            ['userId', '=', $userId],
            ['lectureArea', '=', $parameters['lectureArea']],
            ['lectureTheme', '=', $parameters['lectureTheme']]
        ])->delete();
    }

    /**
     * @description query to set given lecture to user`s lectures list
     * @param $userId
     * @param $parameters
     */
    public function setUserLecture($userId, $parameters) {
        UserLecturesList::create([
            'userId' => $userId,
            'lectureArea' => $parameters['lectureArea'],
            'lectureTheme' => $parameters['lectureTheme'],
            'experience' => $parameters['experience'],
            'price' => $parameters['price']
        ]);
    }

    /**
     * @description query to get user`s lectures list
     * @param $userId
     * @return mixed
     */
    public function getUserLecturesList($userId) {
        $queryResult = UserLecturesList::where('userId', $userId)->get();

        return $queryResult;
    }

    /**
     * @description query to get user`s selected lecture info
     * @param Integer $userId
     * @param Object $parameters
     * @return mixed query result
     */
    public function getUserSelectedLecture($userId, $parameters) {
        $queryResult = UserLecturesList::where([
            ['userId', '=', $userId],
            ['lectureArea', '=', $parameters['lectureArea']],
            ['lectureTheme', '=', $parameters['lectureTheme']]
        ])->first();

        return $queryResult;
    }

}
