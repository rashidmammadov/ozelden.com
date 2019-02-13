<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Util\Image;
use JWTAuth;
use Validator;
use Tymon\JWTAuth\Exceptions\JWTException;
use App\Http\Queries\MySQL\ApiQuery;
use App\Repository\Transformers\ProfileTransformer;


class ProfileController extends ApiController {

    protected $profileTransformer;

    public function __construct(ProfileTransformer $profileTransformer) {
        $this->profileTransformer = $profileTransformer;
    }

    /**
     * @description Get profile of user.
     * @return mixed
     */
    public function getUserProfile() {
        try {
            $user = JWTAuth::parseToken()->authenticate();
            $userId = $user->id;

            $profile = ApiQuery::getUserProfile($userId);
            return $this->respondCreated(null, $this->profileTransformer->transform($profile));
        } catch (JWTException $e) {
            $this->setStatusCode($e->getStatusCode());
            return $this->respondWithError($e->getMessage());
        }
    }

    /**
     * @description Handle request to update profile info.
     * @param Request $request
     * @return mixed
     */
    public function updateProfile(Request $request) {
        try {
            $user = JWTAuth::parseToken()->authenticate();
            $userId = $user->id;

            $params = array(
                PHONE => $request[PHONE],
                COUNTRY => $request[COUNTRY],
                CITY => $request[CITY],
                DISTRICT => $request[DISTRICT],
                ADDRESS => $request[ADDRESS],
                LANGUAGE => $request[LANGUAGE]
            );
            ApiQuery::updateUserProfile($userId, $params);
            return $this->respondCreated('CHANGES_UPDATED_SUCCESSFULLY');
        } catch (JWTException $e) {
            $this->setStatusCode($e->getStatusCode());
            return $this->respondWithError($e->getMessage());
        }
    }

    /**
     * @description Handle request to upload picture.
     * @param Request $request
     * @return mixed
     */
    public function uploadProfilePicture(Request $request) {
        try {
            $user = JWTAuth::parseToken()->authenticate();
            $userId = $user->id;
            $userType = $user->type;
            $rules = array(
                BASE64 => 'required',
                FILE_TYPE => 'required'
            );

            $validator = Validator::make($request->all(), $rules);
            if ($validator->fails()) {
                return $this->respondValidationError('FIELDS_VALIDATION_FAILED', $validator->errors());
            } else {
                $picture = Image::uploadProfilePicture($userId, $userType, $request[BASE64], $request[FILE_TYPE]);
                $params = array(PICTURE => $picture);
                ApiQuery::updateUserProfile($userId, $params);
                return $this->respondCreated('PICTURE_UPLOADED_SUCCESSFULLY', $picture);
            }
        } catch (JWTException $e) {
            $this->setStatusCode($e->getStatusCode());
            return $this->respondWithError($e->getMessage());
        }
    }

}
