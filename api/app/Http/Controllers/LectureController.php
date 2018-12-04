<?php

namespace App\Http\Controllers;

use App\UserLecturesList;
use JWTAuth;
use Validator;
use Illuminate\Http\Request;
use Tymon\JWTAuth\Exceptions\JWTException;

class LectureController extends ApiController {

    public function __construct() {
        // TODO:
    }

    /**
     * @description Add lecture to user`s lectures list
     * @param Request $request
     * @return json
     */
    public function addToUserLectureList(Request $request) {
        try {
            $user = JWTAuth::parseToken()->authenticate();
            $userId = $user->id;
            $rules = array(
                'lectureArea' => 'required',
                'lectureTheme' => 'required',
                'experience' => 'required',
                'price' => 'required'
            );

            $validator = Validator::make($request->all(), $rules);
            if ($validator->fails()) {
                return $this->respondValidationError("FIELDS_VALIDATION_FAILED", $validator->errors());
            } else {
                UserLecturesList::create([
                    'userId' => $userId,
                    'lectureArea' => $request['lectureArea'],
                    'lectureTheme' => $request['lectureTheme'],
                    'experience' => $request['experience'],
                    'price' => $request['price']
                ]);

                return $this->respondCreated("LECTURE_ADDED_SUCCESSFULLY");
            }
        } catch (JWTException $e) {
            $this->setStatusCode($e->getStatusCode());
            return $this->respondWithError($e->getMessage());
        }
    }

    /**
     * @description Get user`s lectures list
     * @param Request $request
     * @return json
     */
    public function getUserLectureList(Request $request) {
        try {
            JWTAuth::getToken();
            $rules = array('id' => 'required');
            $validator = Validator::make($request->all(), $rules);
            if ($validator->fails()) {
                return $this->respondValidationError("FIELDS_VALIDATION_FAILED", $validator->errors());
            } else {
                $userId = $request['id'];
                if ($request['average'] == true) {
                    return $this->userLecturesListWithAverage($userId);                  
                } else {
                    return $this->userLecturesListWithoutAverage($userId);
                }
            }
        } catch (JWTException $e) {
            $this->setStatusCode($e->getStatusCode());
            return $this->respondWithError($e->getMessage());
        }
    }

    /**
     * @description Get user`s lectures list with lecture average.
     * @param Request $request
     * @return json
     */
    public function userLecturesListWithAverage($userId) {
        $userLecturesList = UserLecturesList::where('userId', $userId);
    }

    /**
     * @description Get user`s lectures list without lecture average.
     * @param Request $request
     * @return json
     */
    public function userLecturesListWithoutAverage($userId) {
        $userLecturesList = UserLecturesList::where('userId', $userId);
    }
}
