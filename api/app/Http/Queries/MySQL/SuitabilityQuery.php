<?php

namespace App\Http\Queries\MySQL;

use App\SuitableCourseType;
use App\SuitableFacility;
use App\SuitableLocation;
use App\SuitableRegion;
use Illuminate\Database\QueryException;
use Illuminate\Support\Facades\Log;

class SuitabilityQuery extends Query {

    /**
     * Remove all regions of given tutor from db.
     * @param $tutorId - holds the tutor id.
     * @return mixed
     */
    public static function deleteRegions($tutorId) {
        try {
            SuitableRegion::where(TUTOR_ID, EQUAL_SIGN, $tutorId)
                ->delete();
            return true;
        } catch (QueryException $e) {
            self::logException($e, debug_backtrace());
        }
    }

    /**
     * Get course type of tutor from DB.
     * @param $tutorId - holds the tutor id.
     * @return mixed
     */
    public static function getCourseType($tutorId) {
        try {
            $query = SuitableCourseType::where(TUTOR_ID, EQUAL_SIGN, $tutorId)->first();
            return $query;
        } catch (QueryException $e) {
            self::logException($e, debug_backtrace());
        }
    }

    /**
     * Get facility of tutor from DB.
     * @param $tutorId - holds the tutor id.
     * @return mixed
     */
    public static function getFacility($tutorId) {
        try {
            $query = SuitableFacility::where(TUTOR_ID, EQUAL_SIGN, $tutorId)->first();
            return $query;
        } catch (QueryException $e) {
            self::logException($e, debug_backtrace());
        }
    }

    /**
     * Get location of tutor from DB.
     * @param $tutorId - holds the tutor id.
     * @return mixed
     */
    public static function getLocation($tutorId) {
        try {
            $query = SuitableLocation::where(TUTOR_ID, EQUAL_SIGN, $tutorId)->first();
            return $query;
        } catch (QueryException $e) {
            self::logException($e, debug_backtrace());
        }
    }

    /**
     * Get regions of tutor from DB.
     * @param $tutorId - holds the tutor id.
     * @return mixed
     */
    public static function getRegions($tutorId) {
        try {
            $query = SuitableRegion::where(TUTOR_ID, EQUAL_SIGN, $tutorId)->get();
            return $query;
        } catch (QueryException $e) {
            self::logException($e, debug_backtrace());
        }
    }

    /**
     * Update tutor`s suitable course type.
     * @param $tutorId - holds the tutor id.
     * @param $courseType - holds the course type.
     * @return bool
     */
    public static function updateCourseType($tutorId, $courseType) {
        try {
            SuitableCourseType::updateOrCreate(
                [TUTOR_ID => $tutorId],
                [INDIVIDUAL => $courseType[INDIVIDUAL], GROUP => $courseType[GROUP], CLASS_ => $courseType[CLASS_]]
            );
            return true;
        } catch (QueryException $e) {
            self::logException($e, debug_backtrace());
        }
    }

    /**
     * Update tutor`s suitable facility.
     * @param $tutorId - holds the tutor id.
     * @param $facility - holds the facility.
     * @return bool
     */
    public static function updateFacility($tutorId, $facility) {
        try {
            SuitableFacility::updateOrCreate(
                [TUTOR_ID => $tutorId],
                [DEMO => $facility[DEMO], GROUP_DISCOUNT => $facility[GROUP_DISCOUNT], PACKAGE_DISCOUNT => $facility[PACKAGE_DISCOUNT]]
            );
            return true;
        } catch (QueryException $e) {
            self::logException($e, debug_backtrace());
        }
    }

    /**
     * Update tutor`s suitable location.
     * @param $tutorId - holds the tutor id.
     * @param $location - holds the location.
     * @return bool
     */
    public static function updateLocation($tutorId, $location) {
        try {
            SuitableLocation::updateOrCreate(
                [TUTOR_ID => $tutorId],
                [STUDENT_HOME => $location[STUDENT_HOME], TUTOR_HOME => $location[TUTOR_HOME], ETUDE => $location[ETUDE],
                    COURSE => $location[COURSE], LIBRARY => $location[LIBRARY], OVER_INTERNET => $location[OVER_INTERNET]]
            );
            return true;
        } catch (QueryException $e) {
            self::logException($e, debug_backtrace());
        }
    }

    /**
     * Insert regions of tutor to db.
     * @param $regions - holds the regions of tutor.
     * @return mixed
     */
    public static function updateRegions($regions) {
        try {
            SuitableRegion::insert($regions);
            return true;
        } catch (QueryException $e) {
            self::logException($e, debug_backtrace());
        }
    }
}
