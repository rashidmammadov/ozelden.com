<?php

namespace App\Http\Controllers;

use App\LecturesList;
use Illuminate\Http\Request;

class LectureController extends ApiController {

    public function __construct() {
        // TODO:
    }

    public function addUserLectureList(Request $request) {
        // TODO: add try catch for JWT auth
        $rules = array (
            'lectureArea' => 'required',
            'lectureTheme' => 'required',
            'experience' => 'required',
            'price' => 'required'
        );

        $validator = Validator::make($request->all(), $rules);
        if ($validator-> fails()){
            return $this->respondValidationError("FIELDS_VALIDATION_FAILED", $validator->errors());
        } else {
            LecturesList::create([
                'userId': $userId,
                'lectureArea': $request['lectureArea'],
                'lectureTheme': $request['lectureTheme'],
                'experience': $request['experience'],
                'price': $request['price']
            ]);

            return $this->respondCreated("LECTURE_ADDED_SUCCESSFULLY");
        }
    }
}