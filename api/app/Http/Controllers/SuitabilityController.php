<?php

namespace App\Http\Controllers;

use App\Http\Queries\MySQL\SuitabilityQuery;
use Illuminate\Http\Request;
use Tymon\JWTAuth\Exceptions\JWTException;
use Tymon\JWTAuth\Facades\JWTAuth;
use Validator;

class SuitabilityController extends ApiController {

    /**
     * Handle request to update tutor`s suitability schedule for take a student.
     * @param $type - holds the type of suitability.
     * @param Request $request - holds the updated data.
     * @return mixed
     */
    public function update($type, Request $request) {
        try {
            $user = JWTAuth::parseToken()->authenticate();
            if ($type == COURSE_TYPE) {
                return $this->updateCourseType($user[IDENTIFIER], $request);
            } else if ($type == REGIONS) {
                return $this->updateRegions($user[IDENTIFIER], $request);
            }
        } catch(JWTException $e) {
            $this->setStatusCode(401);
            $this->setMessage(AUTHENTICATION_ERROR);
            return $this->respondWithError($this->getMessage());
        }
    }

    /**
     * Update suitable course types of tutor.
     * @param $tutorId - holds the tutor id.
     * @param $request - holds the course types.
     * @return mixed
     */
    private function updateCourseType($tutorId, $request) {
        $rules = array (
            INDIVIDUAL => 'required',
            GROUP => 'required',
            CLASS_ => 'required'
        );

        $validator = Validator::make($request->all(), $rules);
        if ($validator->fails()) {
            return $this->respondValidationError(FIELDS_VALIDATION_FAILED, $validator->errors());
        } else {
            $updateCourseType = SuitabilityQuery::updateCourseType($tutorId, $request);
            if ($updateCourseType) {
                return $this->respondCreated(COURSE_TYPE_UPDATED_SUCCESSFULLY);
            } else {
                return $this->respondWithError(SOMETHING_WRONG_WITH_DB);
            }
        }
    }

    /**
     * Update the regions of tutor.
     * @param $tutorId - holds the tutor id.
     * @param $request - holds the updated regions.
     * @return mixed
     */
    private function updateRegions($tutorId, $request) {
        $rules = array (
            REGIONS => 'required'
        );

        $validator = Validator::make($request->all(), $rules);
        if ($validator->fails()) {
            return $this->respondValidationError(FIELDS_VALIDATION_FAILED, $validator->errors());
        } else {
            $deleteExistRegions = SuitabilityQuery::deleteRegions($tutorId);
            if ($deleteExistRegions) {
                $regions = array();
                foreach ($request[REGIONS] as $region) {
                    array_push($regions, array(
                        TUTOR_ID => $tutorId,
                        CITY => $region[CITY],
                        DISTRICT => $region[DISTRICT]
                    ));
                }
                $updatedRegions = SuitabilityQuery::updateRegions($regions);
                if ($updatedRegions) {
                    return $this->respondCreated(REGIONS_UPDATED_SUCCESSFULLY);
                } else {
                    return $this->respondWithError(SOMETHING_WRONG_WITH_DB);
                }
            } else {
                return $this->respondWithError(SOMETHING_WRONG_WITH_DB);
            }
        }
    }

}
