<?php

namespace App\Http\Controllers;

use App\Http\Queries\MySQL\ApiQuery;
use JWTAuth;
use Validator;
use Illuminate\Http\Request;
use Tymon\JWTAuth\Exceptions\JWTException;
use App\Http\Controllers\DataController;
use App\Repository\Transformers\UserLectureTransformer;

class LectureController extends ApiController {

    private $dataController;
    private $userLectureTransformer;

    public function __construct(dataController $dataController, userLectureTransformer $userLectureTransformer) {
        $this->dataController = $dataController;
        $this->userLectureTransformer = $userLectureTransformer;
    }

    /**
     * @description Add lecture to user`s lectures list
     * @param Request $request
     * @return mixed
     */
    public function addToUserLectureList(Request $request) {
        try {
            $user = JWTAuth::parseToken()->authenticate();
            $userId = $user->id;
            $rules = array(
                LECTURE_AREA => 'required',
                LECTURE_THEME => 'required',
                EXPERIENCE => 'required',
                PRICE => 'required'
            );

            $validator = Validator::make($request->all(), $rules);
            if ($validator->fails()) {
                return $this->respondValidationError('FIELDS_VALIDATION_FAILED', $validator->errors());
            } else {
                $params = array(
                    LECTURE_AREA => $request[LECTURE_AREA],
                    LECTURE_THEME => $request[LECTURE_THEME],
                    EXPERIENCE => $request[EXPERIENCE],
                    PRICE => $request[PRICE]
                );
                $existLecture = ApiQuery::getUserSelectedLecture($userId, $params);

                if ($existLecture) {
                    return $this->respondWithError('THIS_LECTURE_ALREADY_ADDED');
                } else {
                    ApiQuery::setUserLecture($userId, $params);
                    return $this->respondCreated('LECTURE_ADDED_SUCCESSFULLY');
                }
            }
        } catch (JWTException $e) {
            $this->setStatusCode($e->getStatusCode());
            return $this->respondWithError($e->getMessage());
        }
    }

    /**
     * @description Get user`s lectures list
     * @param Request $request
     * @return mixed
     */
    public function getUserLectureList(Request $request) {
        try {
            $user = JWTAuth::parseToken()->authenticate();
            $userId = $user->id;
            $userLecturesList = ApiQuery::getUserLecturesList($userId);
            if ($request[AVERAGE] == true) {
                $result = $this->userLecturesListWithAverage($userLecturesList);
            } else {
                $result = $this->userLecturesListWithoutAverage($userLecturesList);
            }
            return $this->respondCreated('SUCCESS', $result);
        } catch (JWTException $e) {
            $this->setStatusCode($e->getStatusCode());
            return $this->respondWithError($e->getMessage());
        }
    }

    /**
     * @description Remove selected lecture from user`s lectures list.
     * @param Request $request
     * @return mixed
     */
    public function removeLectureFromUserLectureList(Request $request) {
        try {
            $user = JWTAuth::parseToken()->authenticate();
            $userId = $user->id;
            $rules = array(
                LECTURE_AREA => 'required',
                LECTURE_THEME => 'required'
            );
            $validator = Validator::make($request->all(), $rules);
            if ($validator->fails()) {
                return $this->respondValidationError('FIELDS_VALIDATION_FAILED', $validator->errors());
            } else {
                $params = array(
                    LECTURE_AREA => $request[LECTURE_AREA],
                    LECTURE_THEME => $request[LECTURE_THEME]
                );
                ApiQuery::deleteUserSelectedLecture($userId, $params);
                return $this->respondCreated('LECTURE_REMOVED_SUCCESSFULLY');
            }
        } catch (JWTException $e) {
            $this->setStatusCode($e->getStatusCode());
            return $this->respondWithError($e->getMessage());
        }
    }

    /**
     * @description Get user`s lectures list with lecture average.
     * @param $userLecturesList
     * @return mixed
     */
    public function userLecturesListWithAverage($userLecturesList) {
        $lecturesData = $this->getAllLecturesData();
        $responseList = array();
        foreach ($userLecturesList as $lecture) {
            for ($i = 0; $i < count($lecturesData); $i++) {
                $lectureArea = $lecturesData[$i];
                if ($lectureArea->base == $lecture[LECTURE_AREA]) {
                    for ($j = 0; $j < count($lectureArea->link); $j++) {
                        $lectureTheme = $lectureArea->link[$j];
                        if ($lectureTheme->base == $lecture[LECTURE_THEME]) {
                            $average = $lectureTheme->average->TRY;
                            $lecture[CURRENCY] = TURKISH_LIRA;
                            $r = $this->userLectureTransformer->lectureArrayWithAverage($lecture, $average);
                            array_push($responseList, $r);
                        }
                    }
                }
            }
        }
        return $responseList;
    }

    /**
     * @description Get user`s lectures list without lecture average.
     * @param $userLecturesList
     * @return mixed
     */
    public function userLecturesListWithoutAverage($userLecturesList) {
        $responseList = array();
        foreach ($userLecturesList as $lecture) {
            $lecture[CURRENCY] = TURKISH_LIRA;
            $r = $this->userLectureTransformer->transform($lecture);
            array_push($responseList, $r);
        }
        return $responseList;
    }

    /**
     * @description get all registered lectures data.
     * @return mixed
     */
    private function getAllLecturesData() {
        $request = new Request();
        $request->setMethod('GET');
        $request->replace(['lectures' => true]);
        $response =  $this->dataController->get($request);
        return $response->original['data']->lectures;
    }
}
