<?php

namespace App\Http\Queries\MySQL;

use App\Student;
use Illuminate\Database\QueryException;

class StudentQuery extends Query {

    /**
     * Get all students of tutored user.
     * @param $parentId - holds the tutored user if as parent id.
     * @return mixed
     */
    public static function getParentAllStudents($parentId) {
        try {
            $query = Student::where(PARENT_ID, EQUAL_SIGN, $parentId)
                ->get();
            return $query;
        } catch (QueryException $e) {
            self::logException($e, debug_backtrace());
        }
    }

    /**
     * Save given student on DB.
     * @param $student - holds the student data.
     * @return mixed
     */
    public static function save($student) {
        try {
            $query = Student::create([
                TYPE => !empty($student[TYPE]) ? $student[TYPE] : STUDENT,
                PARENT_ID => $student[PARENT_ID],
                PICTURE => !empty($student[PICTURE]) ? $student[PICTURE] : null,
                NAME => $student[NAME],
                SURNAME => $student[SURNAME],
                BIRTHDAY => $student[BIRTHDAY],
                SEX => $student[SEX]
            ]);
            return $query;
        } catch (QueryException $e) {
            self::logException($e, debug_backtrace());
        }
    }
}
