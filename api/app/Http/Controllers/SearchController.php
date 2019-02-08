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

    /**
     * @description: Handle search request.
     * @param Request $request
     * @return mixed: search result
     */
    public function search(Request $request) {
        try {
            JWTAuth::parseToken()->authenticate();
            $userType = $request[TYPE];

            $result = null;
            if ($userType == TUTOR) {
                $result = $this->searchTutor($request);
            }
            return $this->respondCreated('SUCCESS', $result);
        } catch (JWTException $e) {
            $this->setStatusCode($e->getStatusCode());
            return $this->respondWithError($e->getMessage());
        }
    }

    /**
     * @description: Prepare data for search result.
     * @param Request $request
     * @return mixed: search result
     */
    private function searchTutor($request) {
        $lectureArea = $request[LECTURE_AREA];
        $lectureTheme = $request[LECTURE_THEME];
        $city = $request[CITY];
        $district = $request[DISTRICT];
        $maxPrice = $request[MAX_PRICE];
        $result =  ApiQuery::searchTutor($lectureArea, $lectureTheme, $city, $district, $maxPrice);
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
        return $users;
    }
}
