<?php

namespace App\Repository\Transformers;


class SearchTransformer extends Transformer {

    public function transform($item) {}

    public function tutor($user) {
        return [
            IDENTIFIER => $user->id,
            NAME => $user->name,
            SURNAME => $user->surname,
            BIRTH_DATE =>  intval($user->birthDate),
            EMAIL => $user->email,
            SEX => $user->sex,
            PHONE => $user->phone,
            PICTURE => $user->picture,
            REGISTER_DATE => strtotime($user->created_at) * 1000,
            AVERAGE => 9,
            EXPRESSION => 8.2,
            DISCIPLINE => 4.3,
            CONTACT => 8.5,
            CITY => $user->city,
            DISTRICT => $user->district,
            LECTURES_LIST => $user->lecturesList,
            FACILITY => json_decode($user->facility)
        ];
    }
}
