<?php

namespace App\Http\Queries\MySQL;

use App\TutorLecture;
use Illuminate\Database\QueryException;

class TutorLectureQuery extends Query {

    /**
     * Check if given lecture is already exist on tutor`s lectures list.
     * @param $tutorId - holds the tutor id.
     * @param $lectureArea - holds the lecture area.
     * @param $lectureTheme - holds the lecture theme.
     * @return mixed
     */
    public static function check($tutorId, $lectureArea, $lectureTheme) {
        try {
            $query = TutorLecture::where(TUTOR_ID, EQUAL_SIGN, $tutorId)
                ->where(LECTURE_AREA, EQUAL_SIGN, $lectureArea)
                ->where(LECTURE_THEME, EQUAL_SIGN, $lectureTheme)
                ->exists();
            return $query;
        } catch (QueryException $e) {
            self::logException($e, debug_backtrace());
        }
    }

    /**
     * Delete given lecture of tutor from DB.
     * @param $tutorId - holds the tutor id.
     * @param $tutorLectureId - holds the tutor lecture id.
     * @return mixed
     */
    public static function delete($tutorId, $tutorLectureId) {
        try {
            $query = TutorLecture::where(TUTOR_ID, EQUAL_SIGN, $tutorId)
                ->where(TUTOR_LECTURE_ID, EQUAL_SIGN, $tutorLectureId)
                ->delete();
            return $query;
        } catch (QueryException $e) {
            self::logException($e, debug_backtrace());
        }
    }

    /**
     * Get all lectures of tutor.
     * @param $tutorId - holds the tutor id.
     * @return mixed
     */
    public static function get($tutorId) {
        try {
            $query = TutorLecture::where(TUTOR_ID, EQUAL_SIGN, $tutorId)
                ->get();
            return $query;
        } catch (QueryException $e) {
            self::logException($e, debug_backtrace());
        }
    }

    /**
     * Save tutor lecture on DB.
     * @param $tutorId - holds the tutor id.
     * @param $lecture - holds the lecture data.
     * @return mixed
     */
    public static function save($tutorId, $lecture) {
        try {
            $query = TutorLecture::create([
                TUTOR_ID => $tutorId,
                LECTURE_AREA => $lecture[LECTURE_AREA],
                LECTURE_THEME => $lecture[LECTURE_THEME],
                EXPERIENCE => !is_null($lecture[EXPERIENCE]) ? $lecture[EXPERIENCE] : 0,
                PRICE => $lecture[PRICE],
                CURRENCY => !is_null($lecture[CURRENCY]) ? $lecture[CURRENCY] : 'TRY'
            ]);
            return $query;
        } catch (QueryException $e) {
            self::logException($e, debug_backtrace());
        }
    }

}
