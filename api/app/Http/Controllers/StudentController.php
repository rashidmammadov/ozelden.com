<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use JWTAuth;
use Tymon\JWTAuth\Exceptions\JWTException;
use Validator;

class StudentController extends ApiController {

    public function set(Request $request) {
        try {
            $user = JWTAuth::parseToken()->authenticate();
//            STUDENT_ID, TYPE, PARENT_ID, PICTURE, NAME, SURNAME, BIRTHDAY, SEX
            $rules = array (
                NAME => 'required',
                SURNAME => 'required',
                BIRTHDAY => 'required',
                SEX => 'required'
            );

            $validator = Validator::make($request->all(), $rules);
            if ($validator->fails()) {
                return $this->respondValidationError(FIELDS_VALIDATION_FAILED, $validator->errors());
            } else {
                if ($user[TYPE] == TUTORED) {
                    $parentId = $user[IDENTIFIER];
                } else {
                    return $this->respondWithError(PERMISSION_DENIED);
                }
            }
        } catch (JWTException $e) {
            $this->setStatusCode(401);
            $this->setMessage(AUTHENTICATION_ERROR);
            return $this->respondWithError($e->getMessage());
        }
    }

}
