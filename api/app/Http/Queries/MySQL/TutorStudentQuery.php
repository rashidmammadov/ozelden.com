<?php

namespace App\Http\Queries\MySQL;

use App\TutorStudent;
use Illuminate\Database\QueryException;

class TutorStudentQuery extends Query {

    /**
     * Check DB if relation exist between two users.
     * @param $user1Id - holds the first user id.
     * @param $user2Id - holds the second user id.
     * @return mixed
     */
    public static function checkRelationIfExist($user1Id, $user2Id) {
        try {
            $query = TutorStudent::where(function ($query) use ($user1Id, $user2Id) {
                    $query->where(TUTOR_ID, EQUAL_SIGN, $user1Id)
                        ->where(USER_ID, EQUAL_SIGN, $user2Id);
                })
                ->orWhere(function ($query) use ($user1Id, $user2Id) {
                    $query->where(TUTOR_ID, EQUAL_SIGN, $user2Id)
                        ->where(USER_ID, EQUAL_SIGN, $user1Id);
                })
                ->exists();
            return $query;
        } catch (QueryException $e) {
            self::logException($e, debug_backtrace());
        }
    }

    /**
     * Set student of tutor in DB.
     * @param $data - holds the data;
     * @return mixed
     */
    public static function create($data) {
        try {
            $query = TutorStudent::create([
                TUTOR_ID => $data[TUTOR_ID],
                USER_ID => $data[USER_ID],
                STUDENT_ID => $data[STUDENT_ID],
                OFFER_ID => $data[OFFER_ID]
            ]);
            return $query;
        } catch (QueryException $e) {
            self::logException($e, debug_backtrace());
        }
    }

    /**
     * Get students of given tutor.
     * @param $tutorId - holds the tutor id.
     * @return mixed
     */
    public static function get($tutorId) {
        try {
            $query = TutorStudent::where(TUTOR_ID, EQUAL_SIGN, $tutorId)
                ->get();
            return $query;
        } catch (QueryException $e) {
            self::logException($e, debug_backtrace());
        }
    }

    /**
     * Get students count of given tutor.
     * @param $tutorId - holds the tutor id.
     * @return mixed
     */
    public static function getStudentsCount($tutorId) {
        try {
            $query = TutorStudent::where(TUTOR_ID, EQUAL_SIGN, $tutorId)
                ->distinct(USER_ID, STUDENT_ID)
                ->count();
            return $query;
        } catch (QueryException $e) {
            self::logException($e, debug_backtrace());
        }
    }

}
