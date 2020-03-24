<?php

namespace App\Http\Queries\MySQL;

use App\Announcement;
use Illuminate\Database\QueryException;

class AnnouncementQuery extends Query {

    /**
     * Check if exist any announcement with given parameters.
     * @param $tutoredId - holds the tutored id.
     * @param $announcement - holds the announcement detail.
     * @return mixed
     */
    public static function checkExistsWithParams($tutoredId, $announcement) {
        try {
            $query = Announcement::where(TUTORED_ID, EQUAL_SIGN, $tutoredId)
                ->where(STUDENT_ID, EQUAL_SIGN, $announcement[STUDENT_ID])
                ->where(LECTURE_AREA, EQUAL_SIGN, $announcement[LECTURE_AREA])
                ->where(LECTURE_THEME, EQUAL_SIGN, $announcement[LECTURE_THEME])
                ->where(CITY, EQUAL_SIGN, $announcement[CITY])
                ->where(DISTRICT, EQUAL_SIGN, $announcement[DISTRICT])
                ->exists();
            return $query;
        } catch (QueryException $e) {
            self::logException($e, debug_backtrace());
        }
    }

    /**
     * Save sent announcement on DB.
     * @param $tutoredId - holds the tutored id.
     * @param $announcement - holds the announcement detail.
     * @return mixed
     */
    public static function create($tutoredId, $announcement) {
        try {
            $query = Announcement::create([
                TUTORED_ID => $tutoredId,
                STUDENT_ID => !empty($announcement[STUDENT_ID]) ? $announcement[STUDENT_ID] : null,
                LECTURE_AREA => $announcement[LECTURE_AREA],
                LECTURE_THEME => $announcement[LECTURE_THEME],
                CITY => $announcement[CITY],
                DISTRICT => $announcement[DISTRICT],
                MIN_PRICE => $announcement[MIN_PRICE],
                MAX_PRICE => $announcement[MAX_PRICE],
                CURRENCY => !empty($announcement[CURRENCY]) ? $announcement[CURRENCY] : TURKISH_LIRA,
                SEX => $announcement[SEX],
                STATUS => !empty($announcement[STATUS]) ? $announcement[STATUS] : ANNOUNCEMENT_STATUS_ACTIVE,
            ]);
            return $query;
        } catch (QueryException $e) {
            self::logException($e, debug_backtrace());
        }
    }
}
