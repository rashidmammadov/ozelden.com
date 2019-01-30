<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Intervention\Image\ImageManagerStatic as Image;
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
            $this->updateUserProfile($userId, $params);
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
            $rules = array(
                BASE64 => 'required',
                FILE_TYPE => 'required'
            );

            $validator = Validator::make($request->all(), $rules);
            if ($validator->fails()) {
                return $this->respondValidationError('FIELDS_VALIDATION_FAILED', $validator->errors());
            } else {
                $data = $this->uploadUserProfilePicture($userId, $request[BASE64], $request[FILE_TYPE]);
                return $this->respondCreated('PICTURE_UPLOADED_SUCCESSFULLY', $data);
            }
        } catch (JWTException $e) {
            $this->setStatusCode($e->getStatusCode());
            return $this->respondWithError($e->getMessage());
        }
    }

    /**
     * @description Minify and upload picture of selected user.
     * @param integer $userId
     * @param string {base64} $file
     * @param string $fileType
     * @return array|bool
     */
    public function uploadUserProfilePicture($userId, $file, $fileType) {
        $currentDate = date('siHdmY');
        $fileName = 'user-'.$userId.'-'.$currentDate.'.'.$fileType;
        $path = public_path().'/images/users/' . $fileName;

        $imageDetails = getimagesize($file);
        $originalWidth = $imageDetails[0];
        $originalHeight = $imageDetails[1];
        $newWidth = $originalWidth;
        $newHeight = $originalHeight;
        if ($originalWidth > 350 || $originalHeight > 350) {
            $coefficient = ($originalWidth > $originalHeight) ? ($originalWidth / 350) : ($originalHeight / 350);
            if ($coefficient > 0) {
                $newWidth = $originalWidth / $coefficient;
                $newHeight = $originalHeight / $coefficient;
            }
        }

        Image::make(file_get_contents($file))->resize($newWidth, $newHeight)->save($path);
        $params = array(
            PICTURE => $path
        );
        $this->updateUserProfile($userId, $params);
        return $fileName.' ('.$newWidth.'px-'.$newHeight.'px)';
    }

    /**
     * @description send request to update user`s profile.
     * @param integer $userId
     * @param array $params
     */
    private function updateUserProfile($userId, $params) {
        ApiQuery::updateUserProfile($userId, $params);
    }

}
