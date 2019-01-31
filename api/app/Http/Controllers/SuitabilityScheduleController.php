<?php

namespace App\Http\Controllers;

use App\Http\Queries\MySQL\ApiQuery;
use Illuminate\Http\Request;
use JWTAuth;
use Mockery\Exception;
use Response;
use Validator;
use App\Repository\Transformers\UserSuitabilityScheduleTransformer;
use Tymon\JWTAuth\Exceptions\JWTException;

/**
 * @property UserSuitabilityScheduleTransformer userSuitabilityScheduleTransformer
 */
class SuitabilityScheduleController extends ApiController {

    private $userSuitabilityScheduleTransformer;

    /**
     * SuitabilityScheduleController constructor.
     * @param UserSuitabilityScheduleTransformer $userSuitabilityScheduleTransformer
     */
    public function __construct(userSuitabilityScheduleTransformer $userSuitabilityScheduleTransformer) {
        $this->userSuitabilityScheduleTransformer = $userSuitabilityScheduleTransformer;
    }

    /**
     * @description: create default user suitability schedule
     * @param integer $userId
     */
    public function create($userId) {
        if ($userId) {
            $region =  json_encode($this->userSuitabilityScheduleTransformer->setRegion());
            $location = json_encode($this->userSuitabilityScheduleTransformer->setLocation());
            $courseType = json_encode($this->userSuitabilityScheduleTransformer->setCourseType());
            $facility = json_encode($this->userSuitabilityScheduleTransformer->setFacility());
            $dayHourTable =  json_encode($this->userSuitabilityScheduleTransformer->setDayHourTable());

            ApiQuery::setUserDefaultSuitabilitySchedule($userId, $region, $location, $courseType, $facility, $dayHourTable);
        }
    }

    /**
     * @description: get given user`s suitability schedule.
     * @return mixed
     */
    public function getSuitabilitySchedule() {
        try {
            $user = JWTAuth::parseToken()->authenticate();
            $userId = $user->id;
            $suitabilitySchedule = ApiQuery::getUserSuitabilitySchedule($userId);
            return $this->respondCreated('', $this->userSuitabilityScheduleTransformer->transform($suitabilitySchedule));
        } catch (JWTException $e) {
            $this->setStatusCode($e->getStatusCode());
            return $this->respondWithError($e->getMessage());
        }
    }

    /**
     * @description: get given user`s suitability schedule.
     * @param Request $request
     * @return mixed
     */
    public function updateSuitabilitySchedule(Request $request) {
        try {
            $user = JWTAuth::parseToken()->authenticate();
            $userId = $user->id;
            try {
                ApiQuery::updateUserSuitabilitySchedule($userId, $request);
                return $this->respondCreated('CHANGES_UPDATED_SUCCESSFULLY');
            } catch (Exception $e) {
                $this->setStatusCode($e->getStatusCode());
                return $this->respondWithError($e->getMessage());
            }
        } catch (JWTException $e) {
            $this->setStatusCode($e->getStatusCode());
            return $this->respondWithError($e->getMessage());
        }
    }
}
