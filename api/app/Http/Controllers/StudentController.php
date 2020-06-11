<?php

namespace App\Http\Controllers;

use App\Http\Models\ParentModel;
use App\Http\Models\StudentModel;
use App\Http\Queries\MySQL\StudentQuery;
use App\Http\Queries\MySQL\TutorStudentQuery;
use App\Http\Utilities\Picture;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use JWTAuth;
use Tymon\JWTAuth\Exceptions\JWTException;
use Validator;

class StudentController extends ApiController {

    /**
     * Handle request to delete student.
     * @param $studentId
     * @return mixed
     */
    public function delete($studentId) {
        try {
            $user = JWTAuth::parseToken()->authenticate();
            if ($user[TYPE] == TUTORED) {
                $parentId = $user[IDENTIFIER];
                $deleteStudentsFromDB = StudentQuery::deleteStudent($studentId, $parentId);
                if ($deleteStudentsFromDB) {
                    return $this->respondCreated(STUDENT_DELETED_SUCCESSFULLY);
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
     * Handle request to get students of current user.
     * @return mixed
     */
    public function get() {
        try {
            $user = JWTAuth::parseToken()->authenticate();
            if ($user[TYPE] == TUTOR) {
                $tutorId = $user[IDENTIFIER];
                $studentsFromDB = TutorStudentQuery::get($tutorId);
                if ($studentsFromDB) {
                    $students = array();
                    foreach ($studentsFromDB as $studentFromDB) {
                        $student = new StudentModel();
                        if ($studentFromDB[STUDENT_ID]) {
                            $parent = new ParentModel();
                            $parent->setParentId($studentFromDB[USER_ID]);
                            $parent->setPicture($studentFromDB[PARENT.'_'.PICTURE]);
                            $parent->setName($studentFromDB[PARENT.'_'.NAME]);
                            $parent->setSurname($studentFromDB[PARENT.'_'.SURNAME]);

                            $student->setStudentId($studentFromDB[STUDENT_ID]);
                            $student->setParentId($studentFromDB[USER_ID]);
                            $student->setPicture($studentFromDB[STUDENT.'_'.PICTURE]);
                            $student->setName($studentFromDB[STUDENT.'_'.NAME]);
                            $student->setSurname($studentFromDB[STUDENT.'_'.SURNAME]);
                            $student->setBirthday($studentFromDB[STUDENT.'_'.BIRTHDAY]);
                            $student->setSex($studentFromDB[STUDENT.'_'.SEX]);
                            $student->setParent($parent->get());
                        } else {
                            $student->setStudentId($studentFromDB[USER_ID]);
                            $student->setPicture($studentFromDB[PARENT.'_'.PICTURE]);
                            $student->setName($studentFromDB[PARENT.'_'.NAME]);
                            $student->setSurname($studentFromDB[PARENT.'_'.SURNAME]);
                            $student->setBirthday($studentFromDB[PARENT.'_'.BIRTHDAY]);
                            $student->setSex($studentFromDB[PARENT.'_'.SEX]);
                        }
                        array_push($students, $student->get());
                    }
                    return $this->respondCreated('', array_unique($students, SORT_REGULAR));
                } else {
                    return $this->respondWithError(SOMETHING_WRONG_WITH_DB);
                }
            } else if ($user[TYPE] == TUTORED) {
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
     * Handle request to update student.
     * @param Request $request
     * @return mixed
     */
    public function update(Request $request) {
        try {
            $user = JWTAuth::parseToken()->authenticate();
            $rules = array (
                STUDENT_ID => 'required',
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
                    return $this->updateStudent($user, $request);
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

    /**
     * Updated student on DB.
     * @param $user - holds the user data.
     * @param $request - holds the request.
     * @return mixed
     */
    private function updateStudent($user, $request) {
        $parentId = $user[IDENTIFIER];
        $studentId = $request[STUDENT_ID];
        $studentFromDB = StudentQuery::getStudentById($studentId);
        if ($studentFromDB) {
            $studentModel = new StudentModel($studentFromDB);
            $studentModel->setName($request[NAME]);
            $studentModel->setSurname($request[SURNAME]);
            $studentModel->setBirthday($request[BIRTHDAY]);
            $studentModel->setSex($request[SEX]);
            if ($request[FILE] && $request[FILE][BASE64] && $request[FILE][FILE_TYPE]) {
                $picture = Picture::upload($request[FILE][BASE64], $request[FILE][FILE_TYPE], STUDENT, true);
                if ($picture) {
                    $studentModel->setPicture($picture);
                }
            }
            $updatedStudentDB = StudentQuery::update($studentId, $parentId, $studentModel->get());
            if ($updatedStudentDB) {
                return $this->respondCreated(STUDENT_UPDATED_SUCCESSFULLY);
            } else {
                return $this->respondWithError(SOMETHING_WRONG_WITH_DB);
            }
        } else {
            return $this->respondWithError(SOMETHING_WRONG_WITH_DB);
        }
    }

}
