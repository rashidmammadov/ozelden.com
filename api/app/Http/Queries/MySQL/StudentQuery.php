<?php

namespace App\Http\Queries\MySQL;

use App\Student;
use Illuminate\Database\QueryException;

class StudentQuery extends Query {

    /**
     * Delete student from DB.
     * @param $studentId - holds the student id.
     * @param $parentId - holds the parent id.
     * @return mixed
     */
    public static function deleteStudent($studentId, $parentId) {
        try {
            $query = Student::where(STUDENT_ID, EQUAL_SIGN, $studentId)
                ->where(PARENT_ID, EQUAL_SIGN, $parentId)
                ->delete();
            return $query;
        } catch (QueryException $e) {
            self::logException($e, debug_backtrace());
        }
    }

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
     * Get student with given id.
     * @param $studentId - holds the student id.
     * @return mixed
     */
    public static function getStudentById($studentId) {
        try {
            $query = Student::where(STUDENT_ID, EQUAL_SIGN, $studentId)
                ->first();
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

    /**
     * Update student on DB with matched student id and parent id.
     * @param $studentId - holds the parent id of student.
     * @param $parentId - holds the student id.
     * @param $student - holds the student data.
     * @return bool
     */
    public static function update($studentId, $parentId, $student) {
        try {
            Student::where(STUDENT_ID, EQUAL_SIGN, $studentId)
                ->where(PARENT_ID, EQUAL_SIGN, $parentId)
                ->update([
                    TYPE => !empty($student[TYPE]) ? $student[TYPE] : STUDENT,
                    PICTURE => !empty($student[PICTURE]) ? $student[PICTURE] : null,
                    NAME => $student[NAME],
                    SURNAME => $student[SURNAME],
                    BIRTHDAY => $student[BIRTHDAY],
                    SEX => $student[SEX]
                ]);
            return true;
        } catch (QueryException $e) {
            self::logException($e, debug_backtrace());
        }
    }
}
