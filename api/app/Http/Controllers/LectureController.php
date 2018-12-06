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
                return $this->respondValidationError('FIELDS_VALIDATION_FAILED', $validator->errors());
            } else {
                $existLecture = UserLecturesList::where([
                    ['userId', '=', $userId], 
                    ['lectureArea', '=', $request['lectureArea']], 
                    ['lectureTheme', '=', $request['lectureTheme']]
                ])->first();

                if ($existLecture) {
                    return $this->respondWithError('THIS_LECTURE_ALREADY_ADDED');
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
            $rules = array('userId' => 'required');
            $validator = Validator::make($request->all(), $rules);
            if ($validator->fails()) {
                return $this->respondValidationError("FIELDS_VALIDATION_FAILED", $validator->errors());
            } else {
                $userId = $request['userId'];
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
     * @description Remove selected lecture from user`s lectures list.
     * @param Request $request
     * @return json
     */
    public function removeLectureFromUserLectureList(Request $request) {
        try {
            $user = JWTAuth::parseToken()->authenticate();
            $userId = $user->id;
            $rules = array(
                'lectureArea' => 'required',
                'lectureTheme' => 'required'
            );
            $validator = Validator::make($request->all(), $rules);
            if ($validator->fails()) {
                return $this->respondValidationError("FIELDS_VALIDATION_FAILED", $validator->errors());
            } else {
                UserLecturesList::where([
                    ['userId', '=', $userId], 
                    ['lectureArea', '=', $request['lectureArea']], 
                    ['lectureTheme', '=', $request['lectureTheme']]
                ])->delete();

                return $this->respondCreated("LECTURE_REMOVED_SUCCESSFULLY");
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
        // TODO: create request and call function.
        $request->request->add(['variable', 'value']);
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
