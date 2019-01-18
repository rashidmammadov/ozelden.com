<?php

namespace App\Http\Controllers;

use App\Http\Queries\MySQL\ApiQuery;
use Illuminate\Http\Request;
use JWTAuth;
use Tymon\JWTAuth\Exceptions\JWTException;
use Validator;

class UserClassListController extends ApiController {

    private $dbQuery;

    public function __construct(apiQuery $apiQuery) {
        $this->dbQuery = $apiQuery;
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
     * @description Get user`s class list
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
     * @description prepare response of user`s class list
     * @param integer $userId
     * @return mixed
     */
    public function prepareUserClassList($userId) {
        // TODO;
        $classList = $this->dbQuery->getUserClassList($userId);
        return $classList;
    }
}
