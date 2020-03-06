<?php

namespace App\Http\Controllers;

use App\Http\Models\StudentModel;
use App\Http\Queries\MySQL\StudentQuery;
use App\Http\Utilities\Picture;
use Illuminate\Http\Request;
use JWTAuth;
use Tymon\JWTAuth\Exceptions\JWTException;
use Validator;

class StudentController extends ApiController {

    /**
     * Handle request to get students of current user.
     * @return mixed
     */
    public function get() {
        try {
            $user = JWTAuth::parseToken()->authenticate();
            if ($user[TYPE] == TUTORED) {
                $parentId = $user[IDENTIFIER];
                $studentsFromDB = StudentQuery::getParentAllStudents($parentId);
                if ($studentsFromDB) {
                    $students = array();
                    foreach ($studentsFromDB as $studentFromDB) {
                        $student = new StudentModel($studentFromDB);
                        array_push($students, $student->get());
                    }
                    return $this->respondCreated('', $students);
                } else {
                    return $this->respondWithError(SOMETHING_WRONG_WITH_DB);
                }
            } else {
                return $this->respondWithError(PERMISSION_DENIED);
            }
        } catch (JWTException $e) {
            $this->setStatusCode(401);
            $this->setMessage(AUTHENTICATION_ERROR);
            return $this->respondWithError($e->getMessage());
        }
    }

    /**
     * Handle request to create new student.
     * @param Request $request
     * @return mixed
     */
    public function set(Request $request) {
        try {
            $user = JWTAuth::parseToken()->authenticate();
            $rules = array (
                NAME => 'required',
                SURNAME => 'required',
                BIRTHDAY => 'required',
                SEX => 'required'
            );

            $validator = Validator::make($request->all(), $rules);
            if ($validator->fails()) {
                return $this->respondValidationError(FIELDS_VALIDATION_FAILED, $validator->errors());
            } else {
                if ($user[TYPE] == TUTORED) {
                    return $this->setStudent($user, $request);
                } else {
                    return $this->respondWithError(PERMISSION_DENIED);
                }
            }
        } catch (JWTException $e) {
            $this->setStatusCode(401);
            $this->setMessage(AUTHENTICATION_ERROR);
            return $this->respondWithError($e->getMessage());
        }
    }

    /**
     * Set new student on DB.
     * @param $user - holds the user data.
     * @param $request - holds the request.
     * @return mixed
     */
    private function setStudent($user, $request) {
        $parentId = $user[IDENTIFIER];
        $studentModel = new StudentModel($request);
        $studentModel->setParentId($parentId);
        if ($request[FILE] && $request[FILE][BASE64] && $request[FILE][FILE_TYPE]) {
            $picture = Picture::upload($request[FILE][BASE64], $request[FILE][FILE_TYPE], STUDENT, true);
            if ($picture) {
                $studentModel->setPicture($picture);
            }
        }
        $studentFromDB = StudentQuery::save($studentModel->get());
        if ($studentFromDB) {
            $student = new StudentModel($studentFromDB);
            return $this->respondCreated(STUDENT_CREATED_SUCCESSFULLY, $student->get());
        } else {
            return $this->respondWithError(SOMETHING_WRONG_WITH_DB);
        }
    }

}
