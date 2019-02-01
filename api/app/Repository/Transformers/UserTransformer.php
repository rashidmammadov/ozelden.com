<?php

namespace App\Repository\Transformers;

class UserTransformer extends Transformer{

    public function transform($user){
        return [
            TYPE => $user->type,
            IDENTIFIER => $user->id,
            NAME => $user->name,
            SURNAME => $user->surname,
            BIRTH_DATE => $user->birthDate,
            EMAIL => $user->email,
            SEX => $user->sex,
            REMEMBER_TOKEN => $user->remember_token,
            STATE => $user->state
        ];
    }

}
