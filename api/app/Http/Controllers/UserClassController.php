<?php

namespace App\Http\Controllers;

use App\Http\Queries\MySQL\ApiQuery;
use Illuminate\Http\Request;

class UserClassController extends ApiController {

    private $dbQuery;

    public function __construct(apiQuery $apiQuery) {
        $this->dbQuery = $apiQuery;
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
        $classList = $this->dbQuery->getUserClassList($userId);
        return $classList;
    }
}
