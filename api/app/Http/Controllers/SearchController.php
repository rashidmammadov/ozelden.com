<?php

namespace App\Http\Controllers;

use App\Http\Queries\MySQL\ApiQuery;
use App\Http\Controllers\LectureController;
use App\Repository\Transformers\SearchTransformer;
use Illuminate\Http\Request;
use JWTAuth;
use Tymon\JWTAuth\Exceptions\JWTException;

class SearchController extends ApiController {

    protected $searchTransformer;
    protected $lectureList;

    public function __construct(SearchTransformer $searchTransformer, LectureController $lectureController) {
        $this->searchTransformer = $searchTransformer;
        $this->lectureList = $lectureController;
    }

    public function search(Request $request) {
        try {
            JWTAuth::parseToken()->authenticate();
            $userType = $request[TYPE];

            if ($userType == TUTOR) {
                return $this->searchTutor($request);
            }
        } catch (JWTException $e) {
            $this->setStatusCode($e->getStatusCode());
            return $this->respondWithError($e->getMessage());
        }
    }

    private function searchTutor($request) {
        $userType = $request[TYPE];
        $result =  ApiQuery::searchTutor($userType);
        $users = array();
        $distinctUsers = array();
        foreach ($result as $user) {
            if (!in_array($user->id, $distinctUsers)) {
                $distinctUsers[] = $user->id;
                $lectures = ApiQuery::getUserLecturesList($user->id);
                $user[LECTURES_LIST] = $this->lectureList->userLecturesListWithAverage($lectures);
                array_push($users, $this->searchTransformer->tutor($user));
            }

        }
        return $this->respondCreated('', $users);
    }
}
