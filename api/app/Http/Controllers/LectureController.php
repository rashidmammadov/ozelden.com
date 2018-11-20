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
}
