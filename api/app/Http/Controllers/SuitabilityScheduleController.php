<?php

namespace App\Http\Controllers;

use App\SuitabilitySchedule;
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
     * @return void : created successfully.
     */
    public function create($userId) {
        if ($userId) {
            SuitabilitySchedule::create([
                'userId' => $userId,
                'region' => json_encode($this->userSuitabilityScheduleTransformer->setRegion()),
                'location' => json_encode($this->userSuitabilityScheduleTransformer->setLocation()),
                'courseType' => json_encode($this->userSuitabilityScheduleTransformer->setCourseType()),
                'facility' => json_encode($this->userSuitabilityScheduleTransformer->setFacility()),
                'dayHourTable' => json_encode($this->userSuitabilityScheduleTransformer->setDayHourTable())
            ]);
        }
    }

    /**
     * @description: get given user`s suitability schedule.
     * @param Request $request
     * @return json
     */
    public function getSuitabilitySchedule(Request $request) {
        try {
            JWTAuth::getToken();
            $suitabilitySchedule = SuitabilitySchedule::where('userId', $request['id'])->first();
            return $this->respondCreated('', $this->userSuitabilityScheduleTransformer->transform($suitabilitySchedule));
        } catch (JWTException $e) {
            $this->setStatusCode($e->getStatusCode());
            return $this->respondWithError($e->getMessage());
        }
    }

    /**
     * @description: get given user`s suitability schedule.
     * @param Request $request
     * @return json
     */
    public function updateSuitabilitySchedule(Request $request) {
        try {
            JWTAuth::getToken();
            try {
                $schedule = SuitabilitySchedule::where('userId', $request['userId'])->first();
                $schedule->region = json_encode($request['region']);
                $schedule->location = json_encode($request['location']);
                $schedule->courseType = json_encode($request['courseType']);
                $schedule->facility = json_encode($request['facility']);
                $schedule->dayHourTable = json_encode($request['dayHourTable']);
                $schedule->save();
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
