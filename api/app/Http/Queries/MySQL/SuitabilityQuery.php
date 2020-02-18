<?php

namespace App\Http\Queries\MySQL;

use App\SuitableCourseType;
use App\SuitableFacility;
use App\SuitableLocation;
use App\SuitableRegion;
use Illuminate\Database\QueryException;

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
     * @param $types - holds the course types.
     * @return bool
     */
    public static function updateCourseType($tutorId, $types) {
        try {
            SuitableCourseType::updateOrCreate(
                [TUTOR_ID => $tutorId],
                [INDIVIDUAL => $types[INDIVIDUAL], GROUP => $types[GROUP], CLASS_ => $types[CLASS_]]
            );
            return true;
        } catch (QueryException $e) {
            self::logException($e, debug_backtrace());
        }
    }

    /**
     * Update tutor`s suitable facilities.
     * @param $tutorId - holds the tutor id.
     * @param $facilities - holds the facilities.
     * @return bool
     */
    public static function updateFacilities($tutorId, $facilities) {
        try {
            SuitableFacility::updateOrCreate(
                [TUTOR_ID => $tutorId],
                [DEMO => $facilities[DEMO], GROUP_DISCOUNT => $facilities[GROUP_DISCOUNT], PACKAGE_DISCOUNT => $facilities[PACKAGE_DISCOUNT]]
            );
            return true;
        } catch (QueryException $e) {
            self::logException($e, debug_backtrace());
        }
    }

    /**
     * Update tutor`s suitable locations.
     * @param $tutorId - holds the tutor id.
     * @param $locations - holds the locations.
     * @return bool
     */
    public static function updateLocations($tutorId, $locations) {
        try {
            SuitableLocation::updateOrCreate(
                [TUTOR_ID => $tutorId],
                [STUDENT_HOME => $locations[STUDENT_HOME], TUTOR_HOME => $locations[TUTOR_HOME], ETUDE => $locations[ETUDE],
                    COURSE => $locations[COURSE], LIBRARY => $locations[LIBRARY], OVER_INTERNET => $locations[OVER_INTERNET]]
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
