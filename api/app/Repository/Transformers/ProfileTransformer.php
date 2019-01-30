<?php

namespace App\Repository\Transformers;

class ProfileTransformer extends Transformer {

    public function transform($profile) {
        return [
            PICTURE => $profile->picture,
            PHONE => $profile->phone,
            COUNTRY => $profile->country,
            CITY => $profile->city,
            DISTRICT => $profile->district,
            ADDRESS => $profile->address,
            LANGUAGE => $profile->language
        ];
    }
}
