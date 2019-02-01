<?php

namespace App\Repository\Transformers;

class UserLectureTransformer extends Transformer{


    public function transform($lecture) {
        return [
            LECTURE_AREA => $lecture->lectureArea,
            LECTURE_THEME => $lecture->lectureTheme,
            EXPERIENCE => $lecture->experience,
            PRICE => $lecture->price,
            CURRENCY => $lecture->currency
        ];
    }

    public function lectureArrayWithAverage($lecture, $average) {
        return [
            LECTURE_AREA => $lecture->lectureArea,
            LECTURE_THEME => $lecture->lectureTheme,
            EXPERIENCE => $lecture->experience,
            PRICE => $lecture->price,
            CURRENCY => $lecture->currency,
            AVERAGE => $average
        ];
    }
}
