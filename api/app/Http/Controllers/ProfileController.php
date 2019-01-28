<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Intervention\Image\ImageManagerStatic as Image;
use JWTAuth;
use Validator;
use Tymon\JWTAuth\Exceptions\JWTException;


class ProfileController extends ApiController {

    public function __construct() {}

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
        $fileName = 'user-'.$userId.'.'.$fileType;
        $path = public_path().'/images/users/' . $fileName;

        $imageDetails = getimagesize($file);
        $originalWidth = $imageDetails[0];
        $originalHeight = $imageDetails[1];
        $newWidth = $originalWidth;
        $newHeight = $originalHeight;
        if ($originalWidth > 400 || $originalHeight > 400) {
            $coefficient = ($originalWidth > $originalHeight) ? ($originalWidth / 400) : ($originalHeight / 400);
            if ($coefficient > 0) {
                $newWidth = $originalWidth / $coefficient;
                $newHeight = $originalHeight / $coefficient;
            }
        }

        Image::make(file_get_contents($file))->resize($newWidth, $newHeight)->save($path);
        return $fileName.' ('.$newWidth.'px-'.$newHeight.'px)';
    }

}
