<?php

namespace App\Http\Controllers;

use App\Http\Models\TutorLectureModel;
use App\Http\Queries\MySQL\TutorLectureQuery;
use Illuminate\Http\Request;
use Tymon\JWTAuth\Exceptions\JWTException;
use Tymon\JWTAuth\Facades\JWTAuth;
use Validator;

class TutorLectureController extends ApiController {

    /**
     * Handle request to delete given tutor`s lecture from lectures list.
     * @param $tutorLectureId - holds the tutor lecture id.
     * @return mixed
     */
    public function delete($tutorLectureId) {
        try {
            $user = JWTAuth::parseToken()->authenticate();
            $tutorId = $user[IDENTIFIER];
            return $this->deleteTutorLecture($tutorId, $tutorLectureId);
        } catch(JWTException $e) {
            $this->setStatusCode(401);
            $this->setMessage(AUTHENTICATION_ERROR);
            return $this->respondWithError($this->getMessage());
        }
    }

    /**
     * Handle request to returns tutor`s lectures.
     * @return array|mixed
     */
    public function get() {
        try {
            $user = JWTAuth::parseToken()->authenticate();
            $tutorId = $user[IDENTIFIER];
            return $this->getTutorLectures($tutorId);
        } catch(JWTException $e) {
            $this->setStatusCode(401);
            $this->setMessage(AUTHENTICATION_ERROR);
            return $this->respondWithError($this->getMessage());
        }
    }

    /**
     * Handle request to save given lecture of tutor on DB.
     * @param Request $request
     * @return mixed
     */
    public function set(Request $request) {
        try {
            $user = JWTAuth::parseToken()->authenticate();
            $tutorId = $user[IDENTIFIER];
            return $this->setTutorLecture($tutorId, $request);
        } catch(JWTException $e) {
            $this->setStatusCode(401);
            $this->setMessage(AUTHENTICATION_ERROR);
            return $this->respondWithError($this->getMessage());
        }
    }

    /**
     * Send query to delete tutor`s lecture from DB.
     * @param $tutorId - holds the tutor id.
     * @param $tutorLectureId - holds the tutor lecture id.
     * @return mixed
     */
    private function deleteTutorLecture($tutorId, $tutorLectureId) {
        $deletedTutorLecture = TutorLectureQuery::delete($tutorId, $tutorLectureId);
        if ($deletedTutorLecture) {
            return $this->respondCreated(LECTURE_DELETED_FROM_LIST_SUCCESSFULLY);
        } else {
            return $this->respondWithError(SOMETHING_WRONG_WITH_DB);
        }
    }

    /**
     * Send query to get tutor`s lectures.
     * @param $tutorId - holds the tutor id.
     * @return array
     */
    private function getTutorLectures($tutorId) {
        $tutorLectures = array();
        $tutorLecturesFromDB = TutorLectureQuery::get($tutorId);
        if ($tutorLecturesFromDB) {
            foreach ($tutorLecturesFromDB as $tutorLectureFromDB) {
                $tutorLecture = new TutorLectureModel($tutorLectureFromDB);
                $tutorLecture->setPricePleasure($tutorLecture);
                array_push($tutorLectures, $tutorLecture->get());
            }
        } else {
            return $this->respondWithError(SOMETHING_WRONG_WITH_DB);
        }
        return $this->respondCreated('', $tutorLectures);
    }

    /**
     * Send query to save lecture on DB.
     * @param $tutorId - holds the tutor id.
     * @param $request - holds the lecture request.
     * @return mixed
     */
    private function setTutorLecture($tutorId, $request) {
        $rules = array (
            LECTURE_AREA => 'required',
            LECTURE_THEME => 'required',
            PRICE => 'required'
        );

        $validator = Validator::make($request->all(), $rules);
        if ($validator->fails()) {
            return $this->respondValidationError(FIELDS_VALIDATION_FAILED, $validator->errors());
        } else {
            $existLecture = TutorLectureQuery::check($tutorId, $request[LECTURE_AREA], $request[LECTURE_THEME]);
            if (!$existLecture) {
                $addedTutorLecture = TutorLectureQuery::save($tutorId, $request);
                if ($addedTutorLecture) {
                    $tutorLecture = new TutorLectureModel($addedTutorLecture);
                    return $this->respondCreated('', $tutorLecture->get());
                } else {
                    return $this->respondWithError(SOMETHING_WRONG_WITH_DB);
                }
            } else {
                return $this->respondWithError(THIS_LECTURE_ALREADY_EXISTS);
            }
        }
    }
}
