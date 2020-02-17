<?php

namespace App\Http\Queries\MySQL;

use App\SuitableCourseType;
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
