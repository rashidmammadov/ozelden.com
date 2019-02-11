<?php

namespace App\Library;

use Intervention\Image\ImageManagerStatic;

class Image extends ImageManagerStatic {

    /**
     * @description Minify and upload picture of selected user.
     * @param integer $userId
     * @param string $userType
     * @param string $file
     * @param string $fileType
     * @return array|bool
     */
    public static function uploadProfilePicture($userId, $userType, $file, $fileType) {
        $currentDate = date('siHdmY');
        $fileName = $userType.$userId.'-'.$currentDate.'.'.$fileType;
        $dir = '/users/';
        $subPath = env('IMAGES_PATH');
        $path = public_path(). $subPath . $dir . $fileName;

        $imageDetails = getimagesize($file);
        $originalWidth = $imageDetails[0];
        $originalHeight = $imageDetails[1];
        $newWidth = $originalWidth;
        $newHeight = $originalHeight;
        if ($originalWidth > 300 || $originalHeight > 300) {
            $coefficient = ($originalWidth > $originalHeight) ? ($originalWidth / 300) : ($originalHeight / 300);
            if ($coefficient > 0) {
                $newWidth = $originalWidth / $coefficient;
                $newHeight = $originalHeight / $coefficient;
            }
        }

        parent::make(file_get_contents($file))->resize($newWidth, $newHeight)->save($path);
        return env('IMAGES_HOST') . $dir . $fileName;
    }

}
