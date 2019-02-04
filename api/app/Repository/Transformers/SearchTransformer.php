<?php

namespace App\Repository\Transformers;


class SearchTransformer extends Transformer {

    public function transform($item) {}

    public function tutor($user) {
        return [
            IDENTIFIER => $user->id,
            NAME => $user->name,
            SURNAME => $user->surname,
            BIRTH_DATE =>  $user->birthDate,
            EMAIL => $user->email,
            SEX => $user->sex,
            PHONE => $user->phone,
            PICTURE => $user->picture,
            'registerDate' => 713134324000,
            AVERAGE => 9,
            EXPRESSION => 0,
            DISCIPLINE => 4.3,
            CONTACT => 8.5,
            CITY => $user->city,
            DISTRICT => $user->district,
            LECTURES_LIST => $user->lecturesList
        ];
    }
}
