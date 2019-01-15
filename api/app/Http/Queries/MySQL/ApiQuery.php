<?php

namespace App\Http\Queries\MySQL;

use App\UserLecturesList;

class ApiQuery {

    public function __construct() {
        // 
    }

    /* LECTURE QUERIES */
    public function deleteUserSelectedLecture($userId, $parameters) {
        UserLecturesList::where([
            ['userId', '=', $userId],
            ['lectureArea', '=', $parameters['lectureArea']],
            ['lectureTheme', '=', $parameters['lectureTheme']]
        ])->delete();
    }

    public function setUserLecture($userId, $parameters) {
        UserLecturesList::create([
            'userId' => $userId,
            'lectureArea' => $parameters['lectureArea'],
            'lectureTheme' => $parameters['lectureTheme'],
            'experience' => $parameters['experience'],
            'price' => $parameters['price']
        ]);
    }

    public function getUserLecturesList($userId) {
        $queryResult = UserLecturesList::where('userId', $userId)->get();

        return $queryResult;
    }

    public function getUserSelectedLecture($userId, $parameters) {
        $queryResult = UserLecturesList::where([
            ['userId', '=', $userId],
            ['lectureArea', '=', $parameters['lectureArea']],
            ['lectureTheme', '=', $parameters['lectureTheme']]
        ])->first();

        return $queryResult;
    }

}