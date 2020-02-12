<?php

namespace App\Http\Controllers;

use App\Http\Models\ProfileModel;
use App\Http\Queries\MySQL\ProfileQuery;
use App\Http\Utilities\Picture;
use Illuminate\Http\Request;
use JWTAuth;
use Tymon\JWTAuth\Exceptions\JWTException;
use Validator;

class ProfileController extends ApiController {

    /**
     * Get profile of current user from token.
     * @return mixed
     */
    public function getProfile() {
        try {
            $user = JWTAuth::parseToken()->authenticate();
            $userId = $user[IDENTIFIER];
            $profile = ProfileQuery::getProfileById($userId);
            if ($profile) {
                $profileModel = new ProfileModel($profile);
                return $this->respondCreated(null, $profileModel->get());
            } else {
                return $this->respondWithError(SOMETHING_WRONG_WITH_DB);
            }
        } catch (JWTException $e) {
            $this->setStatusCode(401);
            $this->setMessage(AUTHENTICATION_ERROR);
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
            $userId = $user[IDENTIFIER];
            $profile = ProfileQuery::update($userId, $request);
            if ($profile) {
                return $this->respondCreated(CHANGES_UPDATED_SUCCESSFULLY);
            } else {
                return $this->respondWithError(SOMETHING_WRONG_WITH_DB);
            }
        } catch (JWTException $e) {
            $this->setStatusCode(401);
            $this->setMessage(AUTHENTICATION_ERROR);
            return $this->respondWithError($e->getMessage());
        }
    }

    /**
     * Handle request to upload profile picture.
     * @param Request $request
     * @return mixed
     */
    public function uploadProfilePicture(Request $request) {
        try {
            $user = JWTAuth::parseToken()->authenticate();
            $userId = $user->id;
            $rules = array (
                BASE64 => 'required',
                FILE_TYPE => 'required'
            );

            $validator = Validator::make($request->all(), $rules);
            if ($validator->fails()) {
                return $this->respondValidationError(FIELDS_VALIDATION_FAILED, $validator->errors());
            } else {
                $picture = Picture::upload($request[BASE64], $userId, $request[FILE_TYPE]);
                $result = ProfileQuery::updatePicture($userId, $picture);
                if ($result) {
                    return $this->respondCreated(PICTURE_UPLOADED_SUCCESSFULLY, $picture);
                } else {
                    return $this->respondWithError(SOMETHING_WRONG_WITH_DB);
                }
            }
        } catch (JWTException $e) {
            $this->setStatusCode(401);
            $this->setMessage(AUTHENTICATION_ERROR);
            return $this->respondWithError($e->getMessage());
        }
    }

}
