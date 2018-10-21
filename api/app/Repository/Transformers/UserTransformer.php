<?php

namespace App\Repository\Transformers;

class UserTransformer extends Transformer{

    public function transform($user){
        return [
            'type' => $user->type,
            'id' => $user->id,
            'name' => $user->name,
            'surname' => $user->surname,
            'birthDate' => $user->birthDate,
            'email' => $user->email,
            'sex' => $user->sex,
            'remember_token' => $user->remember_token,
            'state' => $user->state
        ];
    }

}
