<?php

namespace App\Http\Models;

class MissingFieldsModel {

    private $picture;
    private $lecture;
    private $region;

    public function __construct($parameters = null) {
        if ($parameters) {
            $this->setPicture($parameters[PICTURE]);
            $this->setLecture($parameters[LECTURE]);
            $this->setRegion($parameters[REGION]);
        }
    }

    public function get() {
        return array(
            PICTURE => $this->getPicture(),
            LECTURE => $this->getLecture(),
            REGION => $this->getRegion()
        );
    }

    public function getPicture() {
        return $this->picture;
    }

    public function setPicture($picture): void {
        $this->picture = $picture;
    }

    public function getLecture() {
        return $this->lecture;
    }

    public function setLecture($lecture): void {
        $this->lecture = $lecture;
    }

    public function getRegion() {
        return $this->region;
    }

    public function setRegion($region): void {
        $this->region = $region;
    }

}
