<?php

namespace App\Repository\Transformers;

class UserLectureTransformer extends Transformer{


    public function transform($lecture) {
        // TODO
    }

    public function lectureArrayWithAverage($lecture, $average) {
        return [
            'lectureArea' => $lecture['lectureArea'],
            'lectureTheme' => $lecture->lectureTheme,
            'experience' => $lecture->experience,
            'price' => $lecture->price,
            'currency' => $lecture->currency,
            'average' => $average
        ];
    }
}
