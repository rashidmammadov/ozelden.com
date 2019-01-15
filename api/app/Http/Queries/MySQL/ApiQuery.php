<?php

namespace App\Http\Queries\MySQL;

use App\UserLecturesList;

class ApiQuery {

    public function __construct() {
        // 
    }

    /* LECTURE QUERIES */
    public function deleteUserSelectedLecture($parameters) {
        UserLecturesList::where([
            ['userId', '=', $parameters['userId']],
            ['lectureArea', '=', $parameters['lectureArea']],
            ['lectureTheme', '=', $parameters['lectureTheme']]
        ])->delete();
    }

    public function setUserLecture($parameters) {
        UserLecturesList::create([
            'userId' => $parameters['userId'],
            'lectureArea' => $parameters['lectureArea'],
            'lectureTheme' => $parameters['lectureTheme'],
            'experience' => $parameters['experience'],
            'price' => $parameters['price']
        ]);
    }

    public function getUserLecturesList($parameters) {
        $queryResult = UserLecturesList::where('userId', $parameters['userId'])->get();

        return $queryResult;
    }

    public function getUserSelectedLecture($parameters) {
        $queryResult = UserLecturesList::where([
            ['userId', '=', $parameters['userId']],
            ['lectureArea', '=', $parameters['lectureArea']],
            ['lectureTheme', '=', $parameters['lectureTheme']]
        ])->first();

        return $queryResult;
    }

}