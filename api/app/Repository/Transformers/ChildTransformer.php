<?php

namespace App\Repository\Transformers;

class ChildTransformer extends Transformer {

    public function transform($child) {
       return [
           CHILD_ID => $child->childId,
           USER_ID => $child->userId,
           PICTURE => $child->picture,
           NAME => $child->name,
           SURNAME => $child->surname,
           SEX => $child->sex,
           BIRTH_DATE => intval($child->birthDate)
       ];
    }
}
