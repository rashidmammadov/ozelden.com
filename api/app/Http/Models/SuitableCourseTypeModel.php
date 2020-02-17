<?php

namespace App\Http\Models;

class SuitableCourseTypeModel {

    private $individual;
    private $group;
    private $class;

    public function __construct($parameters = null) {
        $this->setIndividual($parameters[INDIVIDUAL]);
        $this->setGroup($parameters[GROUP]);
        $this->setClass($parameters[CLASS_]);
    }

    public function get() {
        return array(
            INDIVIDUAL => $this->getIndividual(),
            GROUP => $this->getGroup(),
            CLASS_ => $this->getClass()
        );
    }

    public function getIndividual() {
        return $this->individual;
    }

    public function setIndividual($individual): void {
        $this->individual = $individual;
    }

    public function getGroup() {
        return $this->group;
    }

    public function setGroup($group): void {
        $this->group = $group;
    }

    public function getClass() {
        return $this->class;
    }

    public function setClass($class): void {
        $this->class = $class;
    }

}
