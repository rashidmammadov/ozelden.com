<?php

namespace App\Http\Controllers;

use App\SuitabilitySchedule;
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

    private $dbQuery;
    private $userSuitabilityScheduleTransformer;

    /**
     * SuitabilityScheduleController constructor.
     * @param ApiQuery $apiQuery
     * @param UserSuitabilityScheduleTransformer $userSuitabilityScheduleTransformer
     */
    public function __construct(apiQuery $apiQuery, userSuitabilityScheduleTransformer $userSuitabilityScheduleTransformer) {
        $this->dbQuery = $apiQuery;
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

            $this->dbQuery->setUserDefaultSuitabilitySchedule($userId, $region, $location, $courseType, $facility, $dayHourTable);
            /*SuitabilitySchedule::create([
                USER_ID => $userId,
                REGION => json_encode($this->userSuitabilityScheduleTransformer->setRegion()),
                LOCATION => json_encode($this->userSuitabilityScheduleTransformer->setLocation()),
                COURSE_TYPE => json_encode($this->userSuitabilityScheduleTransformer->setCourseType()),
                FACILITY => json_encode($this->userSuitabilityScheduleTransformer->setFacility()),
                DAY_HOUR_TABLE => json_encode($this->userSuitabilityScheduleTransformer->setDayHourTable())
            ]);*/
        }
    }

    /**
     * @description: get given user`s suitability schedule.
     * @param Request $request
     * @return mixed
     */
    public function getSuitabilitySchedule(Request $request) {
        try {
            JWTAuth::getToken();
            $suitabilitySchedule = $this->dbQuery->getUserSuitabilitySchedule($request[IDENTIFIER]);
            //SuitabilitySchedule::where(USER_ID, $request[IDENTIFIER])->first();
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
            JWTAuth::getToken();
            try {
                //$schedule = SuitabilitySchedule::where(USER_ID, $request[USER_ID])->first();
                $region = json_encode($request[REGION]);
                $location = json_encode($request[LOCATION]);
                $courseType = json_encode($request[COURSE_TYPE]);
                $facility = json_encode($request[FACILITY]);
                $dayHourTable = json_encode($request[DAY_HOUR_TABLE]);

                $this->dbQuery->updateUserSuitabilitySchedule($request[USER_ID], $region, $location, $courseType, $facility, $dayHourTable);
                //$schedule->save();
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
