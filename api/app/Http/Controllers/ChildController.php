<?php

namespace App\Http\Controllers;

use App\Http\Queries\MySQL\ApiQuery;
use App\Library\Image;
use Illuminate\Http\Request;
use JWTAuth;
use Tymon\JWTAuth\Exceptions\JWTException;
use Validator;

class ChildController extends ApiController {

    public function __construct() { }

    public function addNewChild(Request $request) {
        try {
            $user = JWTAuth::parseToken()->authenticate();
            $userId = $user->id;
            $userType = $user->type;

            $rules = array(
                NAME => 'required|max:50',
                SURNAME => 'required|max:99',
                SEX => 'required',
                BIRTH_DATE => 'required'
            );

            $validator = Validator::make($request->all(), $rules);
            if ($validator->fails()) {
                return $this->respondValidationError('FIELDS_VALIDATION_FAILED', $validator->errors());
            } else {
                $picture = null;
                if ($request[PICTURE]) {
                    $picture = Image::uploadProfilePicture($userId, CHILD, $request[PICTURE]->base64, $request[PICTURE]->fileType);
                }
                $params = array(
                    USER_ID => $userId,
                    PICTURE => $picture,
                    NAME => $request[NAME],
                    SURNAME => $request[SURNAME],
                    SEX => $request[SEX],
                    BIRTH_DATE => $request[BIRTH_DATE]
                );

                if ($userType == STUDENT) {
                    // TODO:
                    ApiQuery;
                }
            }
        } catch (JWTException $e) {
            $this->setStatusCode($e->getStatusCode());
            return $this->respondWithError($e->getMessage());
        }
    }

}
