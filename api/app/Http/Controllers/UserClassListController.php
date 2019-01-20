<?php

namespace App\Http\Controllers;

use App\Http\Queries\MySQL\ApiQuery;
use Illuminate\Http\Request;
use JWTAuth;
use Tymon\JWTAuth\Exceptions\JWTException;
use Validator;
use App\Repository\Transformers\ClassTransformer;

class UserClassListController extends ApiController {

    private $dbQuery;
    private $classTransformer;

    public function __construct(apiQuery $apiQuery, classTransformer $classTransformer) {
        $this->dbQuery = $apiQuery;
        $this->classTransformer = $classTransformer;
    }

    /**
     * @description Add class to user`s class list
     * @param Request $request
     * @return mixed
     */
    public function addToUserClassList(Request $request) {
        try {
            $user = JWTAuth::parseToken()->authenticate();
            $userId = $user->id;
            $rules = array(
                CLASS_NAME => 'required|max:30',
                TUTOR_ID => 'required',
                LECTURE_AREA => 'required',
                LECTURE_THEME => 'required'
            );

            $validator = Validator::make($request->all(), $rules);
            if ($validator->fails()) {
                return $this->respondValidationError('FIELDS_VALIDATION_FAILED', $validator->errors());
            } else {
                $params = array(
                    CLASS_NAME => $request[CLASS_NAME],
                    TUTOR_ID => $request[TUTOR_ID],
                    LECTURE_AREA => $request[LECTURE_AREA],
                    LECTURE_THEME => $request[LECTURE_THEME],
                    DAY => json_encode($request[DAY]),
                    CONTENT => json_encode($request[CONTENT])
                );
                $this->dbQuery->setUserClass($userId, $params);
                return $this->respondCreated('CLASS_ADDED_SUCCESSFULLY');
            }
        } catch (JWTException $e) {
            $this->setStatusCode($e->getStatusCode());
            return $this->respondWithError($e->getMessage());
        }
    }

    /**
     * @description Get user`s class list uğurla
     * @return mixed
     */
    public function getUserClassList() {
        try {
            $user = JWTAuth::parseToken()->authenticate();
            $userId = $user->id;
            $data =  $this->prepareUserClassList($userId);
            return $this->respondCreated('SUCCESS', $data);
        } catch (JWTException $e) {
            $this->setStatusCode($e->getStatusCode());
            return $this->respondWithError($e->getMessage());
        }
    }

    /**
     * @description Remove class
     * @param Request $request
     * @return mixed
     */
    public function removeClass(Request $request) {
        try {
            $user = JWTAuth::parseToken()->authenticate();
            $userId = $user->id;
            $rules = array(
                CLASS_ID => 'required'
            );

            $validator = Validator::make($request->all(), $rules);
            if ($validator->fails()) {
                return $this->respondValidationError('FIELDS_VALIDATION_FAILED', $validator->errors());
            } else {
                $classId = $request[CLASS_ID];
                $this->dbQuery->deleteUserClass($userId, $classId);
                return $this->respondCreated('CLASS_REMOVED_SUCCESSFULLY');
            }
        } catch (JWTException $e) {
            $this->setStatusCode($e->getStatusCode());
            return $this->respondWithError($e->getMessage());
        }
    }

    /**
     * @description Update class
     * @param Request $request
     * @return mixed
     */
    public function updateClass(Request $request) {
        try {
            $user = JWTAuth::parseToken()->authenticate();
            $userId = $user->id;
            $rules = array(
                CLASS_ID => 'required',
                CLASS_NAME => 'required|max:30',
                TUTOR_ID => 'required',
                LECTURE_AREA => 'required',
                LECTURE_THEME => 'required'
            );

            $validator = Validator::make($request->all(), $rules);
            if ($validator->fails()) {
                return $this->respondValidationError('FIELDS_VALIDATION_FAILED', $validator->errors());
            } else {
                $classId = $request[CLASS_ID];
                $params = array(
                    CLASS_NAME => $request[CLASS_NAME],
                    TUTOR_ID => $request[TUTOR_ID],
                    LECTURE_AREA => $request[LECTURE_AREA],
                    LECTURE_THEME => $request[LECTURE_THEME],
                    DAY => json_encode($request[DAY]),
                    CONTENT => json_encode($request[CONTENT])
                );
                $this->dbQuery->updateUserClass($userId, $classId, $params);
                return $this->respondCreated('CHANGES_UPDATED_SUCCESSFULLY');
            }
        } catch (JWTException $e) {
            $this->setStatusCode($e->getStatusCode());
            return $this->respondWithError($e->getMessage());
        }
    }

    /**
     * @description prepare response of user`s class list
     * @param integer $userId
     * @return mixed
     */
    public function prepareUserClassList($userId) {
        // TODO;
        $classList = $this->dbQuery->getUserClassList($userId);
        $classArray = array();
        foreach ($classList as $class) {
            $class = $this->classTransformer->transform($class);
            array_push($classArray, $class);
        }
        return $classArray;
    }
}
