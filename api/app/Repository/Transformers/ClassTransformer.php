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
            LECTURE => $class->lectureArea . ' ' . $class->lectureTheme,
            DAY => json_decode($class->day),
            CONTENT => json_decode($class->content)
        ];
    }

}
