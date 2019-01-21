<?php

namespace App\Repository\Transformers;

class ClassTransformer extends Transformer{

    public function transform($class) {
        return [
            CLASS_ID => $class->classId,
            CLASS_NAME => $class->className,
            TUTOR => array(
                TUTOR_ID => $class->tutorId,
                NAME => $class->name . ' ' . $class->surname
            ),
            LECTURE_AREA => $class->lectureArea,
            LECTURE_THEME => $class->lectureTheme,
            CITY => $class->city,
            DISTRICT => $class->district,
            DAY => json_decode($class->day),
            CONTENT => json_decode($class->content),
            STUDENTS => array()
        ];
    }

}
