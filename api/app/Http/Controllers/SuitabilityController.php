<?php

namespace App\Http\Controllers;

use App\Http\Models\SuitabilityModel;
use App\Http\Models\SuitableCourseTypeModel;
use App\Http\Models\SuitableFacilityModel;
use App\Http\Models\SuitableLocationModel;
use App\Http\Models\SuitableRegionModel;
use App\Http\Queries\MySQL\SuitabilityQuery;
use Illuminate\Http\Request;
use Tymon\JWTAuth\Exceptions\JWTException;
use Tymon\JWTAuth\Facades\JWTAuth;
use Validator;

class SuitabilityController extends ApiController {

    /**
     * Handle request to get tutor`s suitability from DB.
     * @return array|mixed
     */
    public function get() {
        try {
            $user = JWTAuth::parseToken()->authenticate();
            return $this->getSuitability($user[IDENTIFIER]);
        } catch(JWTException $e) {
            $this->setStatusCode(401);
            $this->setMessage(AUTHENTICATION_ERROR);
            return $this->respondWithError($this->getMessage());
        }
    }

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
            } else if ($type == FACILITY) {
                return $this->updateFacilities($user[IDENTIFIER], $request);
            } else if ($type == LOCATION) {
                return $this->updateLocations($user[IDENTIFIER], $request);
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
        if (!is_null($request[COURSE_TYPE])) {
            $updateCourseType = SuitabilityQuery::updateCourseType($tutorId, $request[COURSE_TYPE]);
            if ($updateCourseType) {
                return $this->respondCreated(COURSE_TYPE_UPDATED_SUCCESSFULLY);
            } else {
                return $this->respondWithError(SOMETHING_WRONG_WITH_DB);
            }
        } else {
            return $this->respondValidationError(FIELDS_VALIDATION_FAILED, '');
        }
    }

    /**
     * Update suitable facilities of tutor.
     * @param $tutorId - holds the tutor id.
     * @param $request - holds the course types.
     * @return mixed
     */
    private function updateFacilities($tutorId, $request) {
        if (!is_null($request[FACILITY])) {
            $updateCourseType = SuitabilityQuery::updateFacility($tutorId, $request[FACILITY]);
            if ($updateCourseType) {
                return $this->respondCreated(FACILITIES_UPDATED_SUCCESSFULLY);
            } else {
                return $this->respondWithError(SOMETHING_WRONG_WITH_DB);
            }
        } else {
            return $this->respondValidationError(FIELDS_VALIDATION_FAILED, '');
        }
    }

    /**
     * Update suitable locations of tutor.
     * @param $tutorId - holds the tutor id.
     * @param $request - holds the locations.
     * @return mixed
     */
    private function updateLocations($tutorId, $request) {
        if (!is_null($request[LOCATION])) {
            $updateCourseType = SuitabilityQuery::updateLocation($tutorId, $request[LOCATION]);
            if ($updateCourseType) {
                return $this->respondCreated(LOCATIONS_UPDATED_SUCCESSFULLY);
            } else {
                return $this->respondWithError(SOMETHING_WRONG_WITH_DB);
            }
        } else {
            return $this->respondValidationError(FIELDS_VALIDATION_FAILED, '');
        }
    }

    /**
     * Update the regions of tutor.
     * @param $tutorId - holds the tutor id.
     * @param $request - holds the updated regions.
     * @return mixed
     */
    private function updateRegions($tutorId, $request) {
        if (!is_null($request[REGIONS])) {
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
        } else {
            return $this->respondValidationError(FIELDS_VALIDATION_FAILED, '');
        }
    }

    /**
     * Get suitability of tutor.
     * @param $tutorId - holds the tutor id.
     * @return array
     */
    private function getSuitability($tutorId) {
        $suitability = new SuitabilityModel();

        $courseTypeFromDB = SuitabilityQuery::getCourseType($tutorId);
        $courseType = new SuitableCourseTypeModel($courseTypeFromDB);
        $suitability->setCourseType($courseType->get());

        $facilityFromDB = SuitabilityQuery::getFacility($tutorId);
        $facility = new SuitableFacilityModel($facilityFromDB);
        $suitability->setFacility($facility->get());

        $locationFromDB = SuitabilityQuery::getLocation($tutorId);
        $location = new SuitableLocationModel($locationFromDB);
        $suitability->setLocation($location->get());

        $regionsFromDB = SuitabilityQuery::getRegions($tutorId);
        $regions = array();
        foreach ($regionsFromDB as $regionFromDB) {
            $region = new SuitableRegionModel($regionFromDB);
            array_push($regions, $region->get());
        }
        $suitability->setRegions($regions);

        return $this->respondCreated('', $suitability->get());
    }

}
