<?php

namespace App\Http\Utilities;

use Illuminate\Support\Facades\Log;
use Intervention\Image\Exception\ImageException;
use Intervention\Image\ImageManagerStatic as Image;

class Picture {

    /**
     * Upload picture to folder.
     * @param $file - holds the image file.
     * @param $type - holds the type of picture.
     * @param $primaryName - holds the primary name of image.
     * @param $additionalName - holds parameter if additional name added.
     * @return string - the name of uploaded image file.
     */
    public static function upload($file, $type, $primaryName, $additionalName = false) {
        $currentDate = date('siHdmY');
        $fileName = $primaryName . ($additionalName ? $currentDate : '') . '.' . $type;
        $dir = 'users/';
        $subPath = env('IMAGES_PATH');
        $path = public_path() . $subPath . $dir . $fileName;

        list($newWidth, $newHeight) = self::resize($file);
        try {
            Image::make(file_get_contents($file))->resize($newWidth, $newHeight)->save($path);
        } catch (ImageException $e) {
            Log::error('Upload Image: ' . $e->getMessage());
        }
        return $dir . $fileName;
    }

    /**
     * Resize given picture.
     * @param $file - holds the image file.
     * @param int $maxLength - holds the max length of with or height.
     * @return array - return new with and height
     */
    private static function resize($file, int $maxLength = 300): array {
        $imageDetails = getimagesize($file);
        $originalWidth = $imageDetails[0];
        $originalHeight = $imageDetails[1];
        $newWidth = $originalWidth;
        $newHeight = $originalHeight;
        if ($originalWidth > $maxLength || $originalHeight > $maxLength) {
            $coefficient = ($originalWidth > $originalHeight) ?
                ($originalWidth / $maxLength) : ($originalHeight / $maxLength);
            if ($coefficient > 0) {
                $newWidth = $originalWidth / $coefficient;
                $newHeight = $originalHeight / $coefficient;
            }
        }
        return array($newWidth, $newHeight);
    }
}
